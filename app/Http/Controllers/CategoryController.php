<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\DTOs\CategoryDTO;
use App\Models\Category;
use App\Support\Logging\HasFluentLogging;
use App\Enums\LogOperation;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    use HasFluentLogging;

    protected $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $categories = $this->categoryService->listUserCategories();

        return Inertia::render('Categories/Index', [
            'categories' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(CategoryDTO::rules(), CategoryDTO::messages());

            $categoryDTO = CategoryDTO::fromValidated($validated);
            $category = $this->categoryService->create($categoryDTO);

            $this->logCreate('Category', $category->id, [
                'name' => $category->name,
                'color' => $category->color
            ]);

            return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
        } catch (Exception $e) {
            $this->logErrorWithRequest('Category', LogOperation::CREATE, $request, $e, [
                'validation_data' => $request->all()
            ]);

            return back()->withErrors(['error' => 'Erro ao criar categoria: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): Response
    {
        $category = $this->categoryService->findById($category->id);

        if (!$category) {
            abort(404);
        }

        return Inertia::render('Categories/Show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): Response
    {
        $category = $this->categoryService->findById($category->id);

        if (!$category) {
            abort(404);
        }

        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate(
                CategoryDTO::updateRules($category->id),
                CategoryDTO::messages()
            );

            $categoryDTO = CategoryDTO::fromValidated($validated);
            $this->categoryService->update($category->id, $categoryDTO);

            return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erro ao atualizar categoria: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->delete($category->id);

            return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erro ao excluir categoria: ' . $e->getMessage()]);
        }
    }
}
