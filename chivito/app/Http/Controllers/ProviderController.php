<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['image', 'max:2048'],
            'category_ids' => ['required_without:category_slugs', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'category_slugs' => ['required_without:category_ids', 'array', 'min:1'],
            'category_slugs.*' => ['string', 'exists:categories,slug'],
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

        $provider->categories()->sync($categoryIds);

        return response()->json($provider->load('categories'), 201);
    }

    public function show(Provider $provider)
    {
        return $provider->load('categories');
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
            'category_ids' => ['nullable', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'category_slugs' => ['nullable', 'array', 'min:1'],
            'category_slugs.*' => ['string', 'exists:categories,slug'],
        ]);

        $provider->fill([
            'name' => $validated['name'] ?? $provider->name,
            'company_name' => $validated['company_name'] ?? $provider->company_name,
            'phone' => $validated['phone'] ?? $provider->phone,
            'bio' => $validated['bio'] ?? $provider->bio,
            'city' => $validated['city'] ?? $provider->city,
            'zip' => $validated['zip'] ?? $provider->zip,
            'price' => $validated['price'] ?? $provider->price,
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

        return response()->json($provider->load('categories'));
    }

    public function destroy(Request $request, Provider $provider)
    {
        if ($provider->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $provider->categories()->detach();
        $provider->delete();

        return response()->json(['message' => 'Provider removed'], 200);
    }

    public function destroyAll()
    {
        // Clear pivot table first to maintain FK integrity
        \DB::table('provider_category')->truncate();
        Provider::truncate();

        return response()->json(['message' => 'All providers removed'], 200);
    }
}
