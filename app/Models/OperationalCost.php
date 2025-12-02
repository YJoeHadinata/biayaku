<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationalCost extends Model
{
    protected $fillable = [
        'cost_account_id',
        'nominal',
        'tanggal',
        'deskripsi',
        'branch_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function costAccount()
    {
        return $this->belongsTo(CostAccount::class);
    }
}
