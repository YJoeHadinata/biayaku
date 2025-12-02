<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'nama',
        'harga_tambahan',
        'multiplier',
        'deskripsi',
        'branch_id',
    ];

    protected $casts = [
        'harga_tambahan' => 'decimal:2',
        'multiplier' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
