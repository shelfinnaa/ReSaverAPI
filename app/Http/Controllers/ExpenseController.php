<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id', // Ensure category exists in categories table
        ]);

        $user = auth()->user();

        $expense = new Expense([
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'date' => Carbon::now()->format('Y-m-d'), // Set the date in 'YYYY-MM-DD' format
            'category_id' => $request->input('category_id'),
        ]);

        $user->expenses()->save($expense);

        return response()->json(['message' => 'Expense added successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expenses,id',
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date'
        ]);

        $user = auth()->user();
        $id = $request->input('id');

        // Find the expense by ID
        $expense = Expense::findOrFail($id);

        // Check if the user owns the expense
        if ($expense->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Update the expense attributes
        $expense->update([
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'date' => Carbon::parse($request->input('date'))->format('Y-m-d'),
            'category_id' => $request->input('category_id'),
        ]);

        return response()->json(['message' => 'Expense updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expenses,id',
        ]);

        $user = auth()->user();
        $id = $request->input('id');

        // Find the expense by ID
        $expense = Expense::findOrFail($id);

        // Check if the user owns the expense
        if ($expense->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Delete the expense
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
