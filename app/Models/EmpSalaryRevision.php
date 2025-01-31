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
        'status',
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
// dd(Hashids::encode($integerValue, $decimalPlaces));
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

        return floor($integerValue / pow(10, $decimalPlaces));
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
        $monthSal = floor($value / 12);
        $gross =  $monthSal - floor($monthSal * 0.0504); // 5.04%;
        // dd($gross);
        // $gross=20853;
        // Calculate each component
        $basic = floor($gross * 0.4200); // 41.96%
        $hra = floor($gross * 0.168); // 16.8%
        $conveyance = floor($gross * 0.076); // 7.67%
        $medicalAllowance = floor($gross * 0.0600); // 5.99%
        $specialAllowance = floor($gross * 0.275); // 27.5%

        $pf = floor($gross * 0.0504); // 5.04%
        $esi = floor($gross * 0.00753); // 0.753%
        $professionalTax = floor($gross * 0.0096); // 0.96%

        // Calculate total deductions
        $totalDeductions = floor($pf + $esi + $professionalTax); //6.753%

        // Calculate total
        $total = floor($basic + $hra + $conveyance + $medicalAllowance + $specialAllowance);

        // Return all components and total
        return [
            'annual_ctc' => $value,
            'monthly_ctc' => $monthSal,
            'basic' => $basic,
            'hra' => $hra,
            'conveyance' => $conveyance,
            'medical_allowance' => $medicalAllowance,
            'special_allowance' => $specialAllowance,
            'earnings' => $total,
            'gross' => round($gross, 2),
            'pf' => $pf,
            'esi' => $esi,
            'professional_tax' => $professionalTax,
            'total_deductions' => $totalDeductions,
            'net_pay' => $total - $totalDeductions,
            'working_days' => '30'
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

    public static function getComparisionData($current_ctc, $revised_ctc)
    {
        $cur_ctc = $current_ctc;
        $rev_ctc = $revised_ctc;
        if ($current_ctc > 0) {
            $current_ctc = floor($current_ctc / 12);
        }
        if ($revised_ctc > 0) {
            $revised_ctc = floor($revised_ctc / 12);
        }

        // dd( $revised_ctc);
        $basic_percentage = 0.40; // 39.99% of CTC is Basic
        $hra_percentage = 0.16;     // 16.00% of CTC is HRA
        // $conveyance_percentage = 0.073;
        $da_percentage = 0; // 7.30% of CTC is Conveyance
        // $allowances_percentage = 0.3671; // 36.71% of CTC is Allowances

        // Calculate current CTC breakdown
        $current_basic = ceil($current_ctc * $basic_percentage);
        $current_hra = ceil($current_ctc * $hra_percentage);
        $current_conveyance = $current_ctc > 0 ? 1600 : 0;
        $current_da = ceil($current_ctc * $da_percentage);
        $current_specialallowances = $current_ctc - $current_conveyance - $current_hra - $current_basic;
        $current_monthly_ctc = $current_ctc;
        $current_annual_ctc = $cur_ctc;

        // Calculate revised CTC breakdown
        $revised_basic = ceil($revised_ctc * $basic_percentage);
        $revised_hra = ceil($revised_ctc * $hra_percentage);
        $revised_conveyance = $revised_ctc > 0 ? 1600 : 0;
        $revised_da = ceil($revised_ctc * $da_percentage);
        $revised_specialallowances = $revised_ctc - $revised_conveyance - $revised_hra -  $revised_basic;
        $revised_monthly_ctc = $revised_ctc;
        $revised_annual_ctc = $rev_ctc;

        $percentagechange = 0;
        if ($current_ctc > 0 && $revised_ctc == 0) {
            $percentagechange = 0;
        } else {
            $percentagechange = self::percentageChange($cur_ctc, $rev_ctc);
        }
        return [
            'current' => [
                'basic' => number_format($current_basic, 2),
                'hra' => number_format($current_hra, 2),
                'conveyance' => number_format($current_conveyance, 2),
                'da' => number_format($current_da, 2),
                'specialallowances' => number_format($current_specialallowances, 2),
                'monthly_ctc' => number_format($current_monthly_ctc, 2),
                'annual_ctc' => number_format($current_annual_ctc, 2)
            ],

            'revised' => [
                'basic' => number_format($revised_basic, 2),
                'hra' => number_format($revised_hra, 2),
                'conveyance' => number_format($revised_conveyance, 2),
                'da' => number_format($revised_da, 2),
                'specialallowances' => number_format($revised_specialallowances, 2),
                'monthly_ctc' => number_format($revised_monthly_ctc, 2),
                'annual_ctc' => round(ceil($revised_annual_ctc), 2)
            ],
            'percentage_change' => round($percentagechange, 2),
        ];
    }

    public static function getPercentageWiseRevisedSalary($current_ctc, $percentage_change)
    {
        // Calculate the revised CTC based on percentage change
        if ($percentage_change == 0) {
            $revised_ctc = $current_ctc;
        } else {
            $revised_ctc = $current_ctc + ($current_ctc * $percentage_change / 100);
        }

        // Pass current and revised CTC to getComparisionData
        return self::getComparisionData($current_ctc, $revised_ctc);
    }

    public static  function percentageChange($current_ctc, $revised_ctc)
    {
        // Calculate the percentage change
        if ($current_ctc == 0) {
            // Avoid division by zero if current CTC is zero
            return $revised_ctc > 0 ? 100 : 0; // If CTC is zero, it means it's a complete change
        }

        $percentage_change = (($revised_ctc - $current_ctc) / $current_ctc) * 100;

        return $percentage_change;
    }
}
