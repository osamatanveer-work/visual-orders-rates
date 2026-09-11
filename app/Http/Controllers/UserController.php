<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::paginate(10);
        return view('users.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return back()->with('success', 'User has been created successfully');
        } catch (Throwable $th) {
            return back()->with('error', 'User has not created');
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
    public function show()
    {
        $user = Auth::user();
        return view('contacts.profile', compact('user'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email_address' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8', // Optional: add validation rules for password
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user's data
        $user->name = $request->input('name');
        $user->bio = $request->input('bio');
        $user->phone_no = $request->input('phone_no');
        $user->location = $request->input('location');

        $user->email = $request->input('email_address');

        // Check if password is provided and update it
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the updated user
        $user->save();

        // Redirect back with success message or do any other operation
        return redirect()->back()->with('success', 'User details updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            User::destroy($id);
            return back()->with('success', "User has been deleted successfully");
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
