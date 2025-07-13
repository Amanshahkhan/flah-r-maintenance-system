<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // Client who submitted the request
        'vehicle_number',
        'vehicle_color',
        'vehicle_model',
        'chassis_number', // ✅ ADD THIS
        'vehicle_images',
        'pdf_document_path', // ✅ ADD THIS,
        'parts_select',
        'parts_manual',
         'quantity', // ✅ ADDED
        'total_cost', // ✅ ADDED
        'status',
        'representative_id', // This will now be FK to representatives.id
        'assigned_at',
        'completed_at',
        'rejected_at',
        'rejection_reason',
        'parts_receipt_doc_path',      // ✅ Add this
       'install_complete_doc_path', // ✅ Add this


    ];

    protected $casts = [
        'vehicle_images' => 'array',
        'parts_select' => 'array',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

     /**
     * Get the client (user) that owns the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the representative assigned to the request.
     * THIS IS THE NEW METHOD YOU NEED TO ADD
     */
    public function representative()
    {
        // This links the 'representative_id' column in this model's table
        // to the 'id' column in the 'representatives' table.
        return $this->belongsTo(Representative::class);
    }
public function products()
{
    return $this->belongsToMany(Product::class, 'maintenance_request_product')
                ->withPivot('quantity', 'price_at_order'); // Makes the extra columns accessible
}
}