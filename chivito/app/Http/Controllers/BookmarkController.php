<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->bookmarks()
            ->with(['categories', 'subcategories'])
            ->latest()
            ->get();
    }

    public function store(Request $request, Provider $provider)
    {
        $request->user()->bookmarks()->syncWithoutDetaching([$provider->id]);
        return response()->json(['message' => 'Bookmarked'], 201);
    }

    public function destroy(Request $request, Provider $provider)
    {
        $request->user()->bookmarks()->detach($provider->id);
        return response()->json(['message' => 'Bookmark removed'], 200);
    }
}
