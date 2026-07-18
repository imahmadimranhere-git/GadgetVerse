<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image',
        'status',
    ];

    // Parent category (agar ye sub-category hai)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories (agar ye main category hai)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Is category ke saare products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}