<?php

namespace App\Http\Controllers;

use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = $request->user()->categories;

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request, StoreCategoryAction $action): RedirectResponse
    {
        $user = $request->user();

        $action->handle($user, $request->validated());

        return redirect()->route('categories.index');
    }

    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): RedirectResponse
    {
        Gate::authorize('update', $category);

        $action->handle($category, $request->validated());

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category, DeleteCategoryAction $action): RedirectResponse
    {
        Gate::authorize('delete', $category);

        $action->handle($category);

        return redirect()->route('categories.index');
    }
}
