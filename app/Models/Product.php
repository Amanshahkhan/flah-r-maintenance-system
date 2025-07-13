<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These MUST match the columns in your migration file.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contract_id', // ✅ ADD THIS
        'item',
        'item_description',
        'specifications',
        'unit',
        'quantity',
        'unit_price',
        'discount',
        'price_after_discount',
        'price_with_vat',
        'total_price',
    ];

// In app/Models/Product.php
 public function contract()
    {
        // A Product belongs to one Contract.
        return $this->belongsTo(Contract::class);
    }

// In app/Models/Product.php
public function maintenanceRequests()
{
    return $this->belongsToMany(MaintenanceRequest::class, 'maintenance_request_product');
}
}