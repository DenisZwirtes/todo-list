<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = auth()->user()
                            ->categories()
                            ->latest()
                            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->categories()->create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('categories.index')
                         ->with('success', __('messages.category_created'));
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return view('categories.show', compact('category'));
    }


    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('categories.show', $category)
                 ->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', __('messages.category_deleted'));
    }
}
