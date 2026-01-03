<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'phone',
        'bio',
        'city',
        'zip',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'provider_category');
    }
}
