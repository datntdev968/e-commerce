<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website_url',
        'published',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_tags',
        'sort_order'
    ];

    protected $casts = [
        'seo_keywords' => 'array',
        'seo_tags' => 'array',
    ];
}
