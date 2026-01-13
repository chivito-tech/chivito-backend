<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Provider;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    public function index()
    {
        return Provider::with(['categories', 'subcategories'])
            ->where('status', '!=', 'inactive')
            ->latest()
            ->get();
    }

    public function myProviders(Request $request)
    {
        return Provider::with(['categories', 'subcategories'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();
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
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['image', 'max:2048'],
            'category_ids' => ['required_without:category_slugs', 'array', 'min:1', 'max:2'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'category_slugs' => ['required_without:category_ids', 'array', 'min:1', 'max:2'],
            'category_slugs.*' => ['string', 'exists:categories,slug'],
            'subcategory_ids' => ['nullable', 'array'],
            'subcategory_ids.*' => ['integer', 'exists:subcategories,id'],
        ]);

        $provider = Provider::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
            'city' => $validated['city'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'price' => $validated['price'] ?? null,
            'status' => 'approved',
        ]);

        if (!empty($validated['photos']) && is_array($validated['photos'])) {
            $uploads = [];
            foreach ($request->file('photos', []) as $index => $file) {
                if ($index >= 3) {
                    break;
                }
                $path = $file->store('providers', 'public');
                $uploads[] = Storage::url($path);
            }

            if (isset($uploads[0])) {
                $provider->photo1 = $uploads[0];
            }
            if (isset($uploads[1])) {
                $provider->photo2 = $uploads[1];
            }
            if (isset($uploads[2])) {
                $provider->photo3 = $uploads[2];
            }

            $provider->save();
        }

        $categoryIds = $validated['category_ids'] ?? [];

        if (!empty($validated['category_slugs'])) {
            $slugIds = Category::whereIn('slug', $validated['category_slugs'])->pluck('id')->all();
            $categoryIds = array_merge($categoryIds, $slugIds);
        }

        $categoryIds = array_unique($categoryIds);

        if (empty($categoryIds)) {
            return response()->json(['error' => 'No valid categories found'], 422);
        }

        if (!empty($validated['subcategory_ids'])) {
            $validSubcategories = Subcategory::whereIn('id', $validated['subcategory_ids'])
                ->whereIn('category_id', $categoryIds)
                ->pluck('id')
                ->all();
            if (count($validSubcategories) !== count($validated['subcategory_ids'])) {
                return response()->json(['error' => 'Invalid subcategories selected'], 422);
            }
        }

        $provider->categories()->sync($categoryIds);

        if (!empty($validated['subcategory_ids'])) {
            $provider->subcategories()->sync($validated['subcategory_ids']);
        }

        return response()->json($provider->load(['categories', 'subcategories']), 201);
    }

    public function show(Provider $provider)
    {
        return $provider->load(['categories', 'subcategories']);
    }

    public function update(Request $request, Provider $provider)
    {
        if ($provider->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'company_name' => ['sometimes', 'string', 'max:255'],
            'phone' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('providers', 'phone')->ignore($provider->id),
            ],
            'bio' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['image', 'max:2048'],
            'category_ids' => ['nullable', 'array', 'min:1', 'max:2'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'category_slugs' => ['nullable', 'array', 'min:1', 'max:2'],
            'category_slugs.*' => ['string', 'exists:categories,slug'],
            'subcategory_ids' => ['nullable', 'array'],
            'subcategory_ids.*' => ['integer', 'exists:subcategories,id'],
            'status' => ['nullable', Rule::in(['approved', 'pending', 'inactive'])],
        ]);

        $provider->fill([
            'name' => $validated['name'] ?? $provider->name,
            'company_name' => $validated['company_name'] ?? $provider->company_name,
            'phone' => $validated['phone'] ?? $provider->phone,
            'bio' => $validated['bio'] ?? $provider->bio,
            'city' => $validated['city'] ?? $provider->city,
            'zip' => $validated['zip'] ?? $provider->zip,
            'price' => $validated['price'] ?? $provider->price,
            'status' => $validated['status'] ?? $provider->status,
        ]);

        if (!empty($validated['photos']) && is_array($validated['photos'])) {
            $uploads = [];
            foreach ($request->file('photos', []) as $index => $file) {
                if ($index >= 3) {
                    break;
                }
                $path = $file->store('providers', 'public');
                $uploads[] = Storage::url($path);
            }

            if (isset($uploads[0])) {
                $provider->photo1 = $uploads[0];
            }
            if (isset($uploads[1])) {
                $provider->photo2 = $uploads[1];
            }
            if (isset($uploads[2])) {
                $provider->photo3 = $uploads[2];
            }
        }

        $provider->save();

        $categoryIds = $validated['category_ids'] ?? [];

        if (!empty($validated['category_slugs'])) {
            $slugIds = Category::whereIn('slug', $validated['category_slugs'])
                ->pluck('id')
                ->all();
            $categoryIds = array_merge($categoryIds, $slugIds);
        }

        $categoryIds = array_unique($categoryIds);

        if (!empty($categoryIds)) {
            $provider->categories()->sync($categoryIds);
        }

        if (array_key_exists('subcategory_ids', $validated)) {
            if (!empty($validated['subcategory_ids']) && !empty($categoryIds)) {
                $validSubcategories = Subcategory::whereIn('id', $validated['subcategory_ids'])
                    ->whereIn('category_id', $categoryIds)
                    ->pluck('id')
                    ->all();
                if (count($validSubcategories) !== count($validated['subcategory_ids'])) {
                    return response()->json(['error' => 'Invalid subcategories selected'], 422);
                }
            }
            $provider->subcategories()->sync($validated['subcategory_ids'] ?? []);
        } else {
            $provider->subcategories()->sync([]);
        }

        return response()->json($provider->load(['categories', 'subcategories']));
    }

    public function destroy(Request $request, Provider $provider)
    {
        if ($provider->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $provider->categories()->detach();
        $provider->subcategories()->detach();
        $provider->delete();

        return response()->json(['message' => 'Provider removed'], 200);
    }

    public function destroyAll()
    {
        // Clear pivot table first to maintain FK integrity
        \DB::table('provider_category')->truncate();
        \DB::table('provider_subcategory')->truncate();
        Provider::truncate();

        return response()->json(['message' => 'All providers removed'], 200);
    }
}
