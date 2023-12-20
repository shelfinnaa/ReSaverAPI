<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function setBudgetWants(Request $request)
{
    return $this->setBudget($request, 1, 'budget_wants');
}

public function setBudgetNeeds(Request $request)
{
    return $this->setBudget($request, 2, 'budget_needs');
}

public function setBudgetSavings(Request $request)
{
    return $this->setBudget($request, 3, 'budget_savings');
}

private function setBudget(Request $request, $categoryId, $inputKey)
{
    $user = Auth::user();

    // Validate the request data
    $request->validate([
        $inputKey => 'required|numeric',
    ]);

    // Add or update the budget for the specified category
    $user->categories()->attach($categoryId, ['budget' => $request->input($inputKey)]);

    return response()->json(['message' => 'Budget updated successfully']);
}


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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
