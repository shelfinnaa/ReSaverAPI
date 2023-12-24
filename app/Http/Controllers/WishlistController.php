<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'waiting_period' => 'required|integer|min:1|max:7', // Assuming the user can input a number between 1 and 7.
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

        // Get the user-specified waiting period in days
        $userSpecifiedDays = $request->input('waiting_period');

        // Calculate the waiting period from the current date
        $waitingPeriod = Carbon::now()->addDays($userSpecifiedDays)->format('Y-m-d H:i:s');

        $wishlist = new Wishlist([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'waiting_period' => $waitingPeriod,
        ]);

        $user->wishlists()->save($wishlist);

        return response()->json(['wishlist' => $wishlist], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {


        $user = auth()->user();


        // Fetch all expenses for the specified month
        $wishlist = Wishlist::where('user_id', $user->id) ->get();

        if ($wishlist->isEmpty()) {
            return response()->json(['message' => 'No expenses found for the specified month']);
        }

        return response()->json(['wishlist' => $wishlist]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'wishlist_id' => 'required|exists:wishlists,id',
        ]);

        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the wishlist by ID
        $wishlist = Wishlist::find($request->input('wishlist_id'));

        $user = auth()->user();
        if ($wishlist->user_id !== $user->id) {
            return response()->json(['error' => 'You do not have permission to delete this wishlist'], 403);
        }

        // Update the status to "done"
        $wishlist->status = 'done';

        // Save the changes
        $wishlist->save();

        // Return a JSON response indicating success
        return response()->json(['message' => 'Wishlist status updated to done'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:wishlists',
    ]);

    // If validation fails, return a JSON response with errors
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $id = $request->input('id');
    // Find the wishlist by ID
    $wishlist = Wishlist::find($id);
    $user = auth()->user();

    if ($wishlist->user_id !== $user->id) {
        return response()->json(['error' => 'You do not have permission to delete this wishlist'], 403);
    }

    // Delete the wishlist
    $wishlist->delete();

    // Return a JSON response indicating success
    return response()->json(['message' => 'Wishlist deleted successfully'], 200);
}

}
