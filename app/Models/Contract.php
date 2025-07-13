<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'contract_number', 'contract_name',
        'contract_date', 'start_date',
        'total_value', 'remaining_value'
    ];

    protected $casts = [
        'contract_date' => 'date:Y-m-d',
        'start_date' => 'date:Y-m-d',
        'total_value' => 'float',
        'remaining_value' => 'float',
    ];
// In app/Models/Contract.php
public function products()
{
    // A Contract has many Products.
    return $this->hasMany(Product::class);
}
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
}