<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    public function index()
    {
        return Provider::with('categories')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:providers,phone'],
            'bio' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'category_ids' => ['required_without:category_slugs', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'category_slugs' => ['required_without:category_ids', 'array', 'min:1'],
            'category_slugs.*' => ['string', 'exists:categories,slug'],
        ]);

        $provider = Provider::create([
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
            'city' => $validated['city'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'price' => $validated['price'] ?? null,
            'status' => 'approved',
        ]);

        $categoryIds = $validated['category_ids'] ?? [];

        if (!empty($validated['category_slugs'])) {
            $slugIds = Category::whereIn('slug', $validated['category_slugs'])->pluck('id')->all();
            $categoryIds = array_merge($categoryIds, $slugIds);
        }

        $categoryIds = array_unique($categoryIds);

        if (empty($categoryIds)) {
            return response()->json(['error' => 'No valid categories found'], 422);
        }

        $provider->categories()->sync($categoryIds);

        return response()->json($provider->load('categories'), 201);
    }

    public function show(Provider $provider)
    {
        return $provider->load('categories');
    }
}
