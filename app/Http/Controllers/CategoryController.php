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

        $existingBudget = $user->categories()->find($categoryId);

        if ($existingBudget) {
            // Update the existing budget
            $existingBudget->pivot->update(['budget' => $request->input($inputKey)]);
            return response()->json(['message' => 'Budget updated successfully']);
        } else {
            // Attach a new budget for the specified category
            $user->categories()->attach($categoryId, ['budget' => $request->input($inputKey)]);
            return response()->json(['message' => 'Budget set successfully']);
        }


    }

    public function BudgetChecker(Request $request)
    {
        $user = Auth::user();
        $userCategories = $user->categories;
        if ($userCategories->isEmpty()) {
            return response()->json(['message' => '0'], 200);
        }
        return response()->json($userCategories, 200);
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

    public function setBudgetSekaligus(Request $request)
{
    $user = Auth::user();

    // Validate the request data
    $request->validate([
        'budget_wants' => 'required|numeric',
        'budget_needs' => 'required|numeric',
        'budget_savings' => 'required|numeric',
    ]);

    $budgets = [
        1 => ['category_id' => 1, 'input_key' => 'budget_wants'],
        2 => ['category_id' => 2, 'input_key' => 'budget_needs'],
        3 => ['category_id' => 3, 'input_key' => 'budget_savings'],
    ];

    foreach ($budgets as $categoryId => $budgetData) {
        $existingBudget = $user->categories()->find($budgetData['category_id']);

        if ($existingBudget) {
            // Update the existing budget
            $existingBudget->pivot->update(['budget' => $request->input($budgetData['input_key'])]);
        } else {
            // Attach a new budget for the specified category
            $user->categories()->attach($budgetData['category_id'], ['budget' => $request->input($budgetData['input_key'])]);
        }
    }

    return response()->json(['message' => 'Budgets set/updated successfully']);
}

}

