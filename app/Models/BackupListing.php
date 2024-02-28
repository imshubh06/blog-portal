<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'mrp',
        'selling_price',
        'publisher',
        'author_name',
        'edition',
        'categories',
        'sku',
        'language',
        'no_of_pages',
        'condition',
        'binding_type',
        'insta_mojo_url',
        'base_url',
    ];
}
