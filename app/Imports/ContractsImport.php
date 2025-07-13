<?php

namespace App\Imports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ContractsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // --- PARSE DATES CAREFULLY ---
        $contractDate = null;
        // The key 'tarykh_alaakd' is correct.
        if (!empty($row['tarykh_alaakd'])) {
            try {
                $contractDate = is_numeric($row['tarykh_alaakd']) ? Date::excelToDateTimeObject($row['tarykh_alaakd']) : \Carbon\Carbon::parse(trim($row['tarykh_alaakd']));
            } catch (\Exception $e) {}
        }

        $startDate = null;
        // ✅ USE THE CORRECT KEY: 'tarykh_albdaa'
        if (!empty($row['tarykh_albdaa'])) {
            try {
                $startDate = is_numeric($row['tarykh_albdaa']) ? Date::excelToDateTimeObject($row['tarykh_albdaa']) : \Carbon\Carbon::parse(trim($row['tarykh_albdaa']));
            } catch (\Exception $e) {}
        }
        
        // --- PARSE NUMBERS CORRECTLY ---
        // ✅ USE THE CORRECT KEY: 'alkym_alagmaly'
        $totalValue = !empty($row['alkym_alagmaly']) ? (float) preg_replace('/[^\d.]/', '', $row['alkym_alagmaly']) : 0;
        
        // ✅ USE THE CORRECT KEY: 'alkym_almtbky'
        $remainingValue = !empty($row['alkym_almtbky']) ? (float) preg_replace('/[^\d.]/', '', $row['alkym_almtbky']) : 0;
        
        // Use updateOrCreate to handle both new and existing contracts
        Contract::updateOrCreate(
            [
                // Column to find the record by (this key 'rkm_alaakd' is correct)
                'contract_number'  => $row['rkm_alaakd'],
            ],
            [
                // Data to update or create with
                'contract_name'    => $row['asm_alaakd'] ?? null,
                'contract_date'    => $contractDate ? $contractDate->format('Y-m-d') : null,
                'start_date'       => $startDate ? $startDate->format('Y-m-d') : null,
                'total_value'      => $totalValue,
                'remaining_value'  => $remainingValue,
            ]
        );

        return null; // Return null because updateOrCreate handled the operation
    }

    public function rules(): array
    {
        // The key 'rkm_alaakd' is correct and required.
        return [
            'rkm_alaakd' => 'required',
        ];
    }
}