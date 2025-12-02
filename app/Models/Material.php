<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'unit',
        'harga_satuan',
        'base_unit',
        'qty_per_unit',
        'deskripsi',
        'branch_id',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
