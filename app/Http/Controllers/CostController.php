<?php

namespace App\Http\Controllers;

use App\Actions\Cost\DeleteCostAction;
use App\Actions\Cost\StoreCostAction;
use App\Actions\Cost\UpdateCostAction;
use App\Http\Requests\Cost\StoreCostRequest;
use App\Http\Requests\Cost\UpdateCostRequest;
use App\Models\Cost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CostController extends Controller
{
    public function index(Request $request): View
    {
        $costs = $request->user()->costs;

        return view('costs.index', compact('costs'));
    }


    public function create(Request $request): View
    {
        $categories = $request->user()->categories;

        return view('costs.create', compact('categories'));
    }


    public function store(StoreCostRequest $request, StoreCostAction $action): RedirectResponse
    {
        $user = $request->user();

        $action->handle($user, $request->validated());

        return redirect()->route('costs.index');
    }


    public function edit(Cost $cost): View
    {
        Gate::authorize('update', $cost);

        $categories = $cost->user->categories;

        return view('costs.edit', compact('cost', 'categories'));
    }


    public function update(UpdateCostRequest $request, Cost $cost, UpdateCostAction $action): RedirectResponse
    {
        Gate::authorize('update', $cost);

        $action->handle($cost, $request->validated());

        return redirect()->route('costs.index');
    }


    public function destroy(Cost $cost, DeleteCostAction $action): RedirectResponse
    {
        Gate::authorize('delete', $cost);

        $action->handle($cost);

        return redirect()->route('costs.index');
    }
}
