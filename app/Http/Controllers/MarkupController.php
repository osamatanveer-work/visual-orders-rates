<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Markup;
use App\Models\MarkupCarrier;
use App\Models\MarkupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class MarkupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Markup::paginate(20);
            return view('markup_templates.index', compact('data'));
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
                'name'
            ]);
            $data = Markup::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            return redirect()->route('markup.show', ['slug' => $data->slug])->with('success', 'Markup has been added successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('markup_templates.create');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $data = Markup::with('markupcarriers', 'markupservices')->where('slug', $slug)->first();
        $carriers = Carrier::all();

        return view('markup_templates.more_detail', compact('data', 'carriers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function attachCarrier(Request $request, $id)
    {


        try {
            $request->validate([
                'carrier_id',
                'markup_fixed',
                'markup_percent',
            ]);

            MarkupCarrier::create([
                'carrier_id' => $request->carrier_id,
                'markup_percent' => $request->markup_percent,
                'markup_fixed' => $request->markup_fixed,
                'countries' => $request->countries ? json_encode($request->countries) : null,
                'markup_id' => $id
            ]);

            return back()->with('success', 'Carrier has been added to markup tempalate');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function updateCarrier(Request $request, $id)
    {


        try {

            $request->validate([
                'carrier_id',
                'markup_fixed',
                'markup_percent',
            ]);

            $data = MarkupCarrier::find($id);

            $data->update([
                'carrier_id' => $request->carrier_id,
                'markup_percent' => $request->markup_percent,
                'markup_fixed' => $request->markup_fixed,
                'countries' => $request->countries ? json_encode($request->countries) : null,
            ]);

            return back()->with('success', 'Carrier has been updated to markup tempalate');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    // Carriers

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name'
            ]);
            $markup = Markup::find($id);
            $markup->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            return back()->with('success', 'Markup has been updated successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteMarkupCarrier($id)
    {
        try {
            MarkupCarrier::destroy($id);
            return back()->with('success', 'Carrier has been deleted successfully');
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
            Markup::destroy($id);
            return back()->with('success', "Markup has been deleted successfully");
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    // Services functions

    public function attachServices(Request $request, $id)
    {

        try {
            $request->validate([
                'carrier_id',
                'services',
                'amount',
                'markup_type'
            ]);

            MarkupService::create([
                'carrier_id' => $request->carrier_id,
                'amount' => $request->amount,
                'markup_type' => $request->markup_type,
                'services' => json_encode($request->services),
                'countries' => $request->countries ? json_encode($request->countries) : null,
                'markup_id' => $id
            ]);
            return back()->with('success', 'Carrier Service has been added to markup tempalate');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function updateServices(Request $request, $id)
    {


        try {
            $request->validate([
                'carrier_id',
                'services',
                'amount',
                'markup_type',
            ]);
            $data = MarkupService::findOrFail($id);


            $data->update([
                'carrier_id' => $request->carrier_id,
                'amount' => $request->amount,
                'markup_type' => $request->markup_type,
                'services' => json_encode($request->services),
                'countries' => $request->countries ? json_encode($request->countries) : null,
            ]);
            return back()->with('success', 'Carrier Service has been added to markup tempalate');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function deleteServices($id)
    {
        try {
            MarkupService::destroy($id);
            return back()->with('success', 'Carrier Service has been deleted successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function carrierAllow(Request $request)
    {
        try {
            $data = MarkupCarrier::find($request->id);

            if (!$data) {
                return response()->json(['message' => 'Carrir not found'], 404);
            }

            $data->api_allowed = $request->api_allowed;
            $data->save();

            $response = [
                'message' => "Carrier status has been successfully updated"
            ];

            return response()->json($response, 200);
        } catch (Throwable $th) {
            $response = [
                'message' => $th->getMessage()
            ];

            return response()->json($response, 500);
        }
    }
}
