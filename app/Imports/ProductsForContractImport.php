<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsForContractImport implements ToModel, WithStartRow
{
    private $contractId;

    public function __construct(int $contractId)
    {
        $this->contractId = $contractId;
    }

    public function startRow(): int
    {
        return 2; // Skip the header row
    }

    public function model(array $row)
    {
        // Skip the row if the Item Description (Column I, index 8) is empty.
        if (empty($row[8])) {
            return null;
        }

        // --- 1. Read the THREE essential base values from the Excel row ---
        $quantity = (int)($row[5] ?? 0);      // Column F: الكمية
        $unitPrice = (float)($row[4] ?? 0); // Column E: السعر الافرادي
        $discountPercent = (float)($row[3] ?? 0); // Column D: الخصم (as a percentage)

        // --- 2. Perform all automatic calculations using the PERCENTAGE formula ---
        $vatRate = 0.15; // 15% VAT rate

        // Formula Step 1: Calculate the price of one item after a percentage discount.
        // We divide by 100 to convert the percentage (e.g., 23) into a decimal (0.23).
        $priceAfterDiscount = $unitPrice - ($unitPrice * ($discountPercent / 100));

        // Formula Step 2: Add VAT to the discounted price of one item.
        $priceWithVat = $priceAfterDiscount * (1 + $vatRate);
        
        // Formula Step 3: Calculate the final total price for the entire quantity.
        $totalPrice = $priceWithVat * $quantity;

        // --- 3. Create the Product model with the read and calculated values ---
        return new Product([
            'contract_id'          => $this->contractId,

            // Values from Excel:
            'item_description'     => $row[8],
            'item'                 => $row[7],
            'unit'                 => $row[6],
            'quantity'             => $quantity,
            'unit_price'           => $unitPrice,
            'discount'             => $discountPercent, // Save the percentage value
            
            // Values calculated by our code:
            'price_after_discount' => round($priceAfterDiscount, 2),
            'price_with_vat'       => round($priceWithVat, 2),
            'total_price'          => round($totalPrice, 2),
        ]);
    }
}