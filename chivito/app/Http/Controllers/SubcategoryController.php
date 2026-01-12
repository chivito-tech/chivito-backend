<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

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
}
