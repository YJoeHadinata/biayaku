<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostAccount extends Model
{
    protected $fillable = [
        'nama',
        'tipe',
        'deskripsi',
    ];

    public function operationalCosts()
    {
        return $this->hasMany(OperationalCost::class);
    }

    public function miscellaneousCosts()
    {
        return $this->hasMany(MiscellaneousCost::class);
    }
}
