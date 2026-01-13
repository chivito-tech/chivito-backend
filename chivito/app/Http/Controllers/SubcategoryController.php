<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcategory::query()->orderBy('name');

        $categoryIds = $request->input('category_ids');
        if (is_array($categoryIds) && !empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:subcategories,slug'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $subcategory = Subcategory::create($validated);

        return response()->json($subcategory, 201);
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:subcategories,slug,' . $subcategory->id],
        ]);

        if (array_key_exists('slug', $validated) && empty($validated['slug']) && !empty($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $subcategory->update($validated);

        return response()->json($subcategory);
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->providers()->detach();
        $subcategory->delete();

        return response()->json(['message' => 'Subcategory removed'], 200);
    }
}
