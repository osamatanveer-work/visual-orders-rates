<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Company;
use App\Models\Markup;
use App\Models\RateQoute;
use App\Models\RateQouteList;
use App\Models\Store;
use App\Services\USPSAPIService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    protected $shippingService;

    /**
     * Display a listing of the resource.
     */

    public function __construct(USPSAPIService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        try {

            $data = Company::paginate(6);
            $markup_templates = Markup::all();
            return view('companies.index', compact('data', 'markup_templates'));
            //code...
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => "required",
                'markup_id' => "required"
            ]);
            if ($request->hasFile('img')) {
                $imgname = imgupload($request->file('img'), 'assets/images/companies', 'jpeg');
            } else {
                $imgname = null;
            }
            Company::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'img' => $imgname,
                'markup_id' => $request->markup_id
            ]);
            return back()->with('success', 'Company has been created successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $all_carriers = Carrier::all();
        $markup_templates = Markup::all();
        $company = Company::with('stores')->where('slug', $slug)->first();
        return view('companies.show', compact('company', 'all_carriers', 'markup_templates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Company::find($id);
        return view('companies.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request_data = $request->validate([
            'name' => "required",

        ]);

        try {
            $company = Company::find($id);
            $imagename = $company->img;
            if ($request->hasFile('img')) {
                $imagename = imgupload($request->file('img'), 'assets/images/companies', 'jpeg');
            }


            $company->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'shop_url' => $request->company_url,
                'description' => $request->description,
                'img' => $imagename,
                'markup_id' => $request->markup_id
            ]);
            return redirect()->route('company.all')->with('success', 'Company has been created successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function rateQoutesList(Request $request, $id)
    {
        // Get the request parameters
        $store_id = $request->input('store');
        $carrier_id = $request->input('carrier');
        $rate_qoute = RateQoute::find($id);

        // Start the query
        $query = RateQouteList::query();

        // Apply filters if they are present
        if (!empty($store_id)) {
            $query->where('store_id', $store_id);
        }
        if (!empty($carrier_id)) {
            $query->where('carrier_id', $carrier_id);
        }
        if (!empty($id)) {
            $query->where('rate_qoute_Id', $id);
        }

        // Paginate the results and append query parameters to the pagination links
        $data = $query->get();

        // Fetch all stores and carriers for the form
        $stores = Store::all();
        $carriers = Carrier::all();

        // Return the view with the data
        return view('rate_qoutes.show', compact('data', 'stores', 'carriers', 'store_id', 'carrier_id', 'rate_qoute'));
    }

    public function rateQoutes(Request $request)
    {
        if ($request->ajax()) {
            $query = RateQoute::with('store');

            // Handle sorting
            if ($request->has('order')) {
                $column = $request->input('columns')[$request->input('order')[0]['column']]['name'];
                $direction = $request->input('order')[0]['dir'];
                $query->orderBy($column, $direction);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('store', function ($row) {
                    return $row->store ? $row->store->name : 'N/A';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    return '<a  href="' . route('rateqoutes.list', ['id' => $row->id]) . '"><i class="fa fa-eye text-info" aria-hidden="true"></i> </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('rate_qoutes.index');
    }

    public function showStoreRateQoute($slug)
    {

        $data = Store::where('slug', $slug)->first();
        return view('rate_qoutes.show', compact('data'));
    }

    public function markupUpdate(Request $request, $id)
    {
        try {
            $data = Company::find($id);
            $data->markup_id = $request->markup_id;
            $data->save();
            return back()->with('success', 'Markup status has been completed');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteRateQoute($id)
    {
        try {
            RateQouteList::where('rate_qoute_id', $id)->delete();
            RateQoute::destroy($id);
            return back()->with('success', "Rate qoute has been deleted successfully");
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Store::where('company_id', $id)->delete();
            $company_name = Company::find($id);
            Company::destroy($id);
            return back()->with("success", $company_name->name . ' has been deleted successfully');
        } catch (Throwable $th) {
            return back()->with("error", $th->getMessage());
        }
    }
}
