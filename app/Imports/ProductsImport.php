<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow; // <-- Use this instead of WithHeadingRow
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

// REMOVED: WithHeadingRow, WithValidation, SkipsOnFailure, SkipsFailures
class ProductsImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        // This tells the importer to start reading data from the second row,
        // skipping the header row.
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
// In app/Imports/ProductsImport.php
// In app/Imports/ProductsImport.php

public function model(array $row)
{
    // Check if a critical column like item description (now at index 8) is empty
    if (empty($row[8])) {
        return null;
    }

    // This mapping is corrected for the Right-to-Left reading order
    // of the Excel library for an Arabic sheet.
    return new Product([
        // These fields are text
        'specifications'       => $row[9], // Left-most column
        'item_description'     => $row[8],
        'item'                 => $row[7],
        'unit'                 => $row[6],
        
        // These fields are numbers
        'quantity'             => (int) $row[5], // Cast to integer to be safe
        'unit_price'           => $row[4],
        'discount'             => $row[3],
        'price_after_discount' => $row[2],
        'price_with_vat'       => $row[1],
        'total_price'          => $row[0], // Right-most column
    ]);
}
}