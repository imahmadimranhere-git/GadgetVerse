<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'specifications',
        'price',
        'discount_price',
        'stock_quantity',
        'thumbnail',
        'status',
    ];

    // Ye product kis category ka hai
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Ye product kis brand ka hai
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Is product ki gallery images
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Is product ke reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Sirf approved reviews (Reviews Moderation feature ke liye)
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // Stock logs is product ke
    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
}