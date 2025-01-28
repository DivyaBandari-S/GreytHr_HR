<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;

class EmpSalaryRevision extends Model
{
    use HasFactory;

    protected $table = 'salary_revisions';

    protected $fillable = [
        'emp_id',
        'current_ctc',
        'revised_ctc',
        'revision_date',
        'revision_type',
        'reason',
        'status'
    ];

    /**
     * Encode and set the current CTC attribute.
     */
    public function setCurrentCtcAttribute($value)
    {
        $this->attributes['current_ctc'] = $value ? $this->encodeCTC($value) : null;
    }

    /**
     * Decode and get the current CTC attribute.
     */
    public function getCurrentCtcAttribute($value)
    {

        return $value ? intval($this->decodeCTC($value)) : 0;
    }

    /**
     * Encode and set the revised CTC attribute.
     */
    public function setRevisedCtcAttribute($value)
    {
        $this->attributes['revised_ctc'] = $value ? $this->encodeCTC($value) : null;
    }

    /**
     * Decode and get the revised CTC attribute.
     */
    public function getRevisedCtcAttribute($value)
    {
        return $value ? $this->decodeCTC($value) : 0;
    }

    /**
     * Encode CTC values with decimal handling.
     */
    private function encodeCTC($value)
    {
        $decimalPlaces = strpos($value, '.') !== false ? strlen(substr(strrchr($value, "."), 1)) : 0;
        $factor = pow(10, $decimalPlaces);
        $integerValue = intval($value * $factor);

        return Hashids::encode($integerValue, $decimalPlaces);
    }

    /**
     * Decode CTC values with decimal handling.
     */
    private function decodeCTC($value)
    {
        Log::info('Decoding CTC: ' . $value);
        $decoded = Hashids::decode($value);

        if (count($decoded) === 0) {
            return 0;
        }

        $integerValue = $decoded[0];
        $decimalPlaces = $decoded[1] ?? 0;

        return floor( $integerValue / pow(10, $decimalPlaces));
    }

    /**
     * Relationship to Employee model.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    static function calculateSalaryComponents($value)
    {
        $monthSal= floor($value /12);
        $gross =  $monthSal- floor($monthSal * 0.0504); // 5.04%;
        // dd($gross);
        // $gross=20853;
        // Calculate each component
        $basic =floor($gross * 0.4200); // 41.96%
        $hra =floor($gross * 0.168);// 16.8%
        $conveyance = floor($gross * 0.076); // 7.67%
        $medicalAllowance = floor($gross * 0.0600); // 5.99%
        $specialAllowance = floor($gross * 0.275); // 27.5%

        $pf = floor($gross * 0.0504); // 5.04%
        $esi = floor($gross * 0.00753); // 0.753%
        $professionalTax = floor($gross * 0.0096); // 0.96%

        // Calculate total deductions
        $totalDeductions =floor( $pf + $esi + $professionalTax); //6.753%

        // Calculate total
        $total = floor($basic + $hra + $conveyance + $medicalAllowance + $specialAllowance);

        // Return all components and total
        return [
            'annual_ctc'=>$value,
            'monthly_ctc'=>$monthSal,
            'basic' => $basic,
            'hra' => $hra,
            'conveyance' => $conveyance,
            'medical_allowance' => $medicalAllowance,
            'special_allowance' => $specialAllowance,
            'earnings' => $total,
            'gross'=> round($gross,2),
            'pf' => $pf,
            'esi' => $esi,
            'professional_tax' => $professionalTax,
            'total_deductions' => $totalDeductions,
            'net_pay'=> $total- $totalDeductions,
            'working_days'=>'30'
        ];
    }

    public static function calculateExperience($hireDate)
    {
        if ($hireDate) {
            $joinDate = Carbon::parse($hireDate);
            $currentDate = Carbon::now();

            // Calculate years, months, and days
            $diff = $currentDate->diff($joinDate);
            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            // Determine which part to display
            if ($years > 0) {
                return "{$years} " . ($years > 1 ? "years" : "year");
            } elseif ($months > 0) {
                return "{$months} " . ($months > 1 ? "months" : "month");
            } elseif ($days > 0) {
                return "{$days} " . ($days > 1 ? "days" : "day");
            } else {
                return "Less than a day";
            }
        }

        return "No hire date provided";
    }
}


