<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Provider $provider)
    {
        return $provider->reviews()
            ->with('user')
            ->latest()
            ->get();
    }

    public function store(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = Review::updateOrCreate(
            [
                'provider_id' => $provider->id,
                'user_id' => $request->user()->id,
            ],
            [
                'rating' => $validated['rating'],
                'description' => $validated['description'] ?? null,
            ]
        );

        return response()->json($review->load('user'), 201);
    }
}
