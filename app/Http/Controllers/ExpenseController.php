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
    public function show(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m', // Validate the input format as 'YYYY-MM'
        ]);

        $user = auth()->user();
        $month = $request->input('month');

        // Fetch all expenses for the specified month
        $expenses = Expense::where('user_id', $user->id)
            ->whereYear('date', '=', substr($month, 0, 4)) // Extract the year from 'YYYY-MM'
            ->whereMonth('date', '=', substr($month, 5, 2)) // Extract the month from 'YYYY-MM'
            ->get();

        if ($expenses->isEmpty()) {
            return response()->json(['message' => 'No expenses found for the specified month']);
        }

        return response()->json(['expenses' => $expenses]);
    }

    public function showWithoutDate(Request $request)
    {

        $user = auth()->user();

        // Fetch all expenses for the specified month
        $expenses = Expense::where('user_id', $user->id)
            ->get();

        if ($expenses->isEmpty()) {
            return response()->json(['message' => 'No expenses found']);
        }

        return response()->json(['expenses' => $expenses]);
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

    public function displayCategoryTotalExpense(Request $request){
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = auth()->user();
        $month = $request->input('month');
        $category_id = $request->input('category_id');

        // Get the sum of expenses for the specified month and category
        $sum = Expense::where('user_id', $user->id)
            ->whereYear('date', '=', substr($month, 0, 4))
            ->whereMonth('date', '=', substr($month, 5, 2))
            ->where('category_id', $category_id)
            ->sum('amount');

        return response()->json(['sum' => $sum ?? 0]);
    }

    public function getTotalExpenses(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $user = auth()->user();
        $selectedMonth = $request->input('month');

        // Get the total expense for both Category 1 and Category 2 combined within the specified month and year
        $totalCombined = Expense::where('user_id', $user->id)
            ->whereYear('date', '=', substr($selectedMonth, 0, 4))
            ->whereMonth('date', '=', substr($selectedMonth, 5, 2))
            ->whereIn('category_id', [1, 2])
            ->sum('amount');

        return response()->json([
            'total_expenses' => $totalCombined
        ]);
    }

    public function getTotalExpensesAndBudgetPercentage(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = auth()->user();
        $month = $request->input('month');
        $category_id = $request->input('category_id');

        // Get the sum of expenses for the specified month and category
        $totalExpense = Expense::where('user_id', $user->id)
            ->whereYear('date', '=', substr($month, 0, 4))
            ->whereMonth('date', '=', substr($month, 5, 2))
            ->where('category_id', $category_id)
            ->sum('amount');

        // Get the user's budget for the specified category
        $userBudget = $user->categories()->where('category_id', $category_id)->value('budget');

        // Calculate the percentage spent from the budget
        $percentageSpent = round(($userBudget > 0) ? ($totalExpense / $userBudget) * 100 : 0);

        return response()->json([
            'total_expense' => $totalExpense ?? 0,
            'budget' => $userBudget ?? 0,
            'percentage_spent' => $percentageSpent,
        ]);
    }

}
