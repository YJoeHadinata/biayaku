<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'jumlah_output',
        'tanggal_produksi',
        'hpp_total',
        'hpp_per_unit',
        'biaya_tambahan',
        'total_hpp_dengan_tambahan',
        'total_hpp_per_unit_final',
        'harga_jual',
        'keterangan',
        'branch_id',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'hpp_total' => 'decimal:2',
        'hpp_per_unit' => 'decimal:2',
        'biaya_tambahan' => 'decimal:2',
        'total_hpp_dengan_tambahan' => 'decimal:2',
        'total_hpp_per_unit_final' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'jumlah_output' => 'decimal:2',
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
