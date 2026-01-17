<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'company_name',
        'phone',
        'bio',
        'city',
        'zip',
        'status',
        'price',
        'photo1',
        'photo2',
        'photo3',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'provider_category');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'provider_subcategory');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'provider_user_bookmarks')
            ->withTimestamps();
    }
}
