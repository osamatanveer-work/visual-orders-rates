<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\MarkupCarrier;
use App\Models\MarkupService;
use App\Models\RateQoute;
use App\Models\RateQouteList;
use App\Models\Store;
use App\Services\FedExAPIService;
use App\Services\ShopifyApiService;
use App\Services\UPSAPIService;
use App\Services\USPSAPIService;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CarrierController extends Controller
{

    protected $fedexServices;
    protected $uspsServices;
    protected $upsServices;

    protected $shopifyServices;


    public function __construct(FedExAPIService $fedexServices, USPSAPIService $uspsServices, ShopifyApiService $shopifyServices, UPSAPIService $upsServices)
    {
        $this->fedexServices = $fedexServices;
        $this->uspsServices = $uspsServices;
        $this->shopifyServices = $shopifyServices;
        $this->upsServices = $upsServices;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Carrier::paginate(6);
        return view('carriers.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'client_id' => 'required',
            'client_secreat_key' => 'required',
        ]);
        $imgname = imgupload($request->file('img'), 'assets/images/carriers', 'jpeg');

        Carrier::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'client_id' => $request->client_id,
            'client_secreat_key' => $request->client_secreat_key,
            'img' => $imgname
        ]);


        return back()->with('success', "Carrier has been created successfully");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $data = Carrier::where('slug', $slug)->first();
        // $services=$this->fedexServices->shippingServices();
        // $services=$this->uspsServices->shippingServices();

        $services = CarrierService::where("carrier_name", $data->name)->get();
        return view('carriers.show', compact('data', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $request_data = $request->validate([
                'name',
                'description',
                'client_id',
                'client_secreat_key'
            ]);

            $carrier = Carrier::find($id);
            $imgname = $carrier->img;
            if ($request->hasFile('img')) {
                $imgname = imgupload($request->file('img'), 'assets/images/carriers', 'jpeg');
            }

            $carrier->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'client_id' => $request->client_id,
                'client_secreat_key' => $request->client_secreat_key,
                'img' => $imgname
            ]);
            return redirect()->route('carrier.all')->with('success', 'Carrier has been updated successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // A Carrier here is a shipping carrier (UPS / FedEx / USPS), NOT a
        // Shopify carrier service. The carriers table has no shopify_id column,
        // so the previous implementation passed null into
        // ShopifyApiService::deleteCarrier(), got a falsy result back, and its
        // `if ($data)` guard then skipped the delete entirely - carrier
        // deletion has never actually worked, and failed silently with a 200.
        //
        // Shopify carrier services are registered per Store and are removed by
        // StoreController::destroy(). There is nothing to call here.
        try {
            $carrier = Carrier::findOrFail($id);
            $name = $carrier->name;

            $carrier->delete();

            return back()->with("success", $name . ' has been deleted successfully');
        } catch (Throwable $th) {
            Log::error('Carrier delete failed', ['carrier' => $id, 'error' => $th->getMessage()]);

            return back()->with("error", $th->getMessage());
        }
    }

    public function updateService(Request $request, $id)
    {
        $data = CarrierService::find($id);
        $data->api_name = $request->api_name;
        $data->save();
        return back()->with('success', 'Service name has been updated successfully');
    }

    public function selectService($id)
    {
        $carrier = Carrier::find($id);
        $data = CarrierService::where('carrier_name', $carrier->name)->get();
        return response()->json($data);
    }

    public function ServiceAllow(Request $request)
    {
        try {
            $data = CarrierService::find($request->service_id);
            if (!$data) {
                return response()->json(['message' => 'Service not found'], 404);
            }
            $data->api_allowed = $request->api_allowed;
            $data->save();
            $response = [
                'message' => "Service status has been successfully updated"
            ];
            return response()->json($response, 200);
        } catch (Throwable $th) {
            $response = [
                'message' => $th->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function CarrierServices(Request $request, $slug)
    {
        Log::info('Received Shopify Callback:', ['request' => $request->all()]);

        $response = ['rates' => []];

        if ($request->has('rate')) {
            Log::info('Rate data:', ['rate' => $request->rate]);

            $origin = $request->rate['origin'];
            $destination = $request->rate['destination'];
            $destinationCountry = strtolower($destination['country']);
            $items = $request->rate['items'];

            // Calculate total weight from items
            $item_quantity = 0;
            $weight = 0;
            foreach ($items as $value) {
                $item_quantity += intval($value['quantity']);
                $weight += $value['grams'] * $value['quantity'];
            }

            // Add box weight if exists
            $box = Box::latest()->first();
            $ouncesToGrams = 28.3495;
            $weight += $box ? $box->package_weight * $ouncesToGrams : 0;

            $store_data = Store::with(['company', 'company.markup'])->where('slug', $slug)->first();
            $markup = $store_data->company->markup;

            // Cache the carrier services and markup data to avoid repeated queries
            $markup_carrier_rate = MarkupCarrier::where('markup_id', $markup->id)->get();
            $markup_services = MarkupService::where('markup_id', $markup->id)->get();

            // Retrieve all service IDs and cache them
            if ($markup_services) {
                $services = $markup_services->pluck('services')->map(function ($service) {
                    return json_decode($service, true);
                })->flatten();
                $all_services = CarrierService::whereIn('id', $services)->get();
            }


            // Prepare API requests
            $currency = $request->rate['currency'];
            $locale = $request->rate['locale'];

            // Fetch and merge rates from different carriers
            try {
                $upsRates = $this->upsServices->shippingRates($origin, $destination, $weight, $currency, $locale);
                $fedexRates = $this->fedexServices->shippingRates($origin, $destination, $weight, $currency, $locale);
                $uspsRates = $this->uspsServices->shippingRates($origin, $destination, $weight, $currency, $locale);
                $allRates = array_merge($upsRates, $fedexRates, $uspsRates);
                //$allRates = array_merge($upsRates, $uspsRates);

                if ($allRates) {
                    $minDeliveryDate = (new DateTime())->modify('+1 day')->format('Y-m-d H:i:s O');
                    $maxDeliveryDate = (new DateTime())->modify('+7 days')->format('Y-m-d H:i:s O');

                    $rateqouteName = 'Rate-Quote ' . (RateQoute::count() + 1);
                    $rateqoute = RateQoute::create([
                        'name' => $rateqouteName,
                        'store_id' => $store_data->id,
                        'store_name' => $store_data->name,
                        'app' => $store_data->slug,
                        'address' => $destination['address1'],
                        'city' => $destination['city'],
                        'province' => $destination['province'],
                        'country' => $destination['country'],
                    ]);


                    foreach ($allRates as $rate) {
                        $total_price = $rate['total_price'];

                        $service = CarrierService::where('code', $rate['service_code'])->first();
                        $carrier = Carrier::where('name', $service->carrier_name)->first();

                        // Apply carrier-specific markup
                        if (!$markup_carrier_rate->isEmpty()) {

                            foreach ($markup_carrier_rate as $value) {

                                if ($value->carrier_id === $carrier->id) {
                                    $countries = json_decode($value->countries, true);

                                    // Convert all country codes in the $countries array to lowercase
                                    if ($countries) {
                                        $countries = array_map('strtolower', $countries);
                                    }


                                    // Check if 'countries' is empty or if it contains the destination country
                                    if (empty($countries) || in_array($destinationCountry, $countries)) {
                                        $total_price += ($value->markup_fixed ?? 0) + ($total_price * ($value->markup_percent / 100));
                                    }
                                }
                            }
                        }

                        // Apply service-specific markup
                        if ($markup_services->isNotEmpty() && $all_services->contains('code', $service->code)) {

                            $carrier_service = $markup_services->where('carrier_id', $carrier->id)->first();
                            if ($carrier_service) {

                                $countries = json_decode($carrier_service->countries, true);

                                // Convert all country codes in the $countries array to lowercase
                                if ($countries) {
                                    $countries = array_map('strtolower', $countries);
                                }

                                // Check if 'countries' is empty or if it contains the destination country
                                if (empty($countries) || in_array($destinationCountry, $countries)) {
                                    $total_price += $carrier_service->markup_type === 'fixed'
                                        ? $carrier_service->amount
                                        : $total_price * ($carrier_service->amount / 100);
                                }
                            }
                        }

                        $total_price_in_cents = (int)($total_price * 100);
                        $total_price = round($total_price_in_cents / 100, 2);
                        $profit = $total_price - $rate['total_price'];


                        $response['rates'][] = [
                            'service_name' => $service->api_name ?? $rate['service_name'],
                            'service_code' => $rate['service_code'],
                            'total_price' => $total_price_in_cents,
                            'currency' => $currency,
                            'min_delivery_date' => $minDeliveryDate,
                            'max_delivery_date' => $maxDeliveryDate,
                        ];

                        RateQouteList::create([
                            'rate_qoute_id' => $rateqoute->id,
                            'store_id' => $store_data->id,
                            'carrier_id' => $carrier->id,
                            'service_name' => $service->api_name ?? $rate['service_name'],
                            'markup_id' => $store_data->company->markup_id,
                            'qoute_amount' => $total_price,
                            'retail_price' => $rate['total_price'],
                            'profit_margin' => $profit,
                        ]);
                    }
                } else {
                    Log::warning('No data returned from carrier APIs.');
                }
            } catch (Exception $e) {
                Log::error('Carrier Service Error:', ['error' => $e->getMessage()]);
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            Log::warning('Rate data is missing in the request.');
        }

        return response()->json($response);
    }
}