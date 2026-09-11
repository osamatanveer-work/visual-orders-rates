<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;
use Throwable;

class BoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Box::paginate(10);
        return view('boxes.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            $existingBoxCount = Box::count();
            if ($existingBoxCount === 0) {
                $request->validate([
                    'package_weight',
                ]);

                Box::create([
                    'name' => $request->name,
                    'length' => $request->length,
                    'width' => $request->width,
                    'height' => $request->height,
                    'package_weight' => $request->package_weight,
                    'max_weight' => $request->max_weight,

                ]);

                return back()->with("success", "Box has been created successfully");
            }
            {
                return back()->with("error", "You have already created box");
            }
        } catch (Throwable $th) {
            return back()->with("error", $th->getMessage());

        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'package_weight',
            ]);
            $box = Box::find($id);
            $box->update([
                'name' => $request->name,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'package_weight' => $request->package_weight,
                'max_weight' => $request->max_weight,


            ]);
            return back()->with("success", "Box has been updated successfully");
        } catch (Throwable $th) {
            return back()->with("error", $th->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Box::destroy($id);
            return back()->with('success', "Box has been deleted successfully");
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());

        }
    }
}
