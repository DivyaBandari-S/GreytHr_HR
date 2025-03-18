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
        'payout_month'
    ];

    /**
     * Encode and set the current CTC attribute.
     */
    public function setCurrentCtcAttribute($value)
    {
        $this->attributes['current_ctc'] = isset($value) ? $this->encodeCTC($value) : null;
        // dd( $this->attributes['current_ctc']);
    }

    /**
     * Decode and get the current CTC attribute.
     */
    public function getCurrentCtcAttribute($value)
    {
        return $value ? intval($this->decodeCTC($value)) : 0;
    }
    public function getSalaryAttribute($value)
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
    public static function decodeCTC($value)
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
        $basic = floor($gross *  0.40); // 41.96%
        $hra = floor($gross * 0.16); // 16.8%
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

            // Calculate years and months difference
            $diff = $currentDate->diff($joinDate);
            $years = $diff->y;
            $months = $diff->m;

            // Build the output string
            $experience = '';

            if ($years > 0) {
                $experience .= "{$years}Y"; // Example: "2y"
            }

            if ($months > 0) {
                $experience .= ($years > 0 ? ', ' : ' ') . "{$months}M"; // Example: "2y, 3m"
            }

            return $experience ?: "0M"; // If no experience, return "0m"
        }

        return '';
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
    static public function changeStatus($id)
    {
        if ($id == 1) {
            return 'Approved';
        } elseif ($id == 0) {
            return 'Pending';
        } elseif ($id == 2) {
            return 'Rejected';
        } else {
            return 'Unknown';
        }
    }

    public static function getFullAndActualSalaryComponents($salary, $revised_ctc, $total_working_days,$lop_days)
    {
        $working_days=$total_working_days- $lop_days;

// dd($salary);
        if ($revised_ctc > 0) {
            $revised_monthly_ctc = floor($revised_ctc / 12);
        }
        $epf_percentage = 0.04802;
        $basic_percentage = 0.42018; // 39.99% of CTC is Basic
        $hra_percentage = 0.168082;
        $pf_percentage = 0.04802;
        $esi_percentage = 0.0075;

        $full_epf = floor($revised_monthly_ctc * $epf_percentage);
        $actual_epf=floor($salary * $epf_percentage);


        $full_pf = floor($revised_monthly_ctc * $pf_percentage);
        $actual_pf = floor($salary * $pf_percentage);

        $full_gross = floor($revised_monthly_ctc -  $full_epf);
        $actual_gross = floor($salary -  $actual_epf);
        // dd( $actual_gross);

        $full_esi = 0;
        $actual_esi=0;
        if ($full_gross <= 21000) {
            $full_esi = ceil($full_gross * $esi_percentage);
            $actual_esi = ceil($full_gross/$total_working_days * $working_days * $esi_percentage);
            // dd($actual_esi);
        }
        $full_prof_tax = 0;
        $actual_prof_tax = 0;
        if ($full_gross > 15000 && $full_gross <= 20000 ) {
            $full_prof_tax = 150;
            $actual_prof_tax = 150;

        } elseif($full_gross > 20000) {
            $full_prof_tax = 200;
            $actual_prof_tax = 200;
        }

        $full_basic = floor($full_gross * $basic_percentage);
        $actual_basic = floor($full_basic/$total_working_days * $working_days);

        $full_hra = floor($full_gross * $hra_percentage);
        $actual_hra = floor($full_hra/$total_working_days * $working_days);
        // dd( $full_hra, $actual_hra);

        $full_conveyance = $full_gross > 0 ? 1600 : 0;
        $actual_conveyance = $full_conveyance/$total_working_days * $working_days;
        // dd($actual_conveyance);
        $full_medical = $full_gross > 0 ? 1250 : 0;
        $actual_medical = $full_medical/$total_working_days * $working_days;
        // dd( $full_gross, $actual_gross );
        $full_special = $full_gross - $full_basic - $full_hra - $full_conveyance - $full_medical;
        // $actual_gross=$actual_basic+ $actual_hra+$actual_conveyance+$actual_medical+$actual_special;
        $actual_special = $actual_gross - $actual_basic - $actual_hra - $actual_conveyance - $actual_medical;
        // dd( $actual_special, $actual_gross,$actual_basic,$actual_hra,$actual_conveyance,$actual_medical);
        $net_salary = $full_gross - $full_pf - $full_esi - $full_prof_tax;
        $actual_net_salary = $actual_gross - $actual_pf - $actual_esi - $actual_prof_tax;
        // dd($actual_net_salary);
        $total_deductions=$full_pf + $full_esi + $full_prof_tax;
        $actual_total_deductions=$actual_pf + $actual_esi + $actual_prof_tax;

        // dd( $actual_total_deductions);
        // dd($full_gross,$full_basic,$full_hra ,$full_conveyance,$full_medical, $full_pf, $full_prof_tax);

        return [
            'full_basic' => $full_basic,
            'full_hra' => $full_hra,
            'full_conveyance' => $full_conveyance,
            'full_medical' => $full_medical,
            'full_special' => $full_special,
            'full_pf' => $full_pf,
            'full_esi' => $full_esi,
            'full_prof_tax' => $full_prof_tax,
            'full_gross' => $full_gross,
            'total_deductions'=>$total_deductions,
            'net_salary' => $net_salary,
            'full_days'=>$total_working_days,

            'actual_basic' => $actual_basic,
            'actual_hra' => $actual_hra,
            'actual_conveyance' => $actual_conveyance,
            'actual_medical' => $actual_medical,
            'actual_special' => $actual_special,
            'actual_pf' => $actual_pf,
            'actual_esi' => $actual_esi,
            'actual_prof_tax' => $actual_prof_tax,
            'actual_gross' => $actual_gross,
            'actual_total_deductions'=>$actual_total_deductions,
            'actual_net_salary' => $actual_net_salary,
            'actual_working_days' => $working_days,
            'lop_days' => $lop_days,
        ];
    }
    public static function convertNumberToWords($number)
    {
        // Array to represent numbers from 0 to 19 and the tens up to 90
        $words = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        ];

        // Handle special cases
        if ($number < 0) {
            return 'minus ' . self::convertNumberToWords(-$number);
        }

        // Handle numbers less than 100
        if ($number < 100) {
            if ($number < 20) {
                return $words[$number];
            } else {
                $tens = $words[10 * (int) ($number / 10)];
                $ones = $number % 10;
                if ($ones > 0) {
                    return $tens . ' ' . $words[$ones];
                } else {
                    return $tens;
                }
            }
        }

        // Handle numbers greater than or equal to 100
        if ($number < 1000) {
            $hundreds = $words[(int) ($number / 100)] . ' hundred';
            $remainder = $number % 100;
            if ($remainder > 0) {
                return $hundreds . ' ' . self::convertNumberToWords($remainder);
            } else {
                return $hundreds;
            }
        }

        // Handle larger numbers
        if ($number < 1000000) {
            $thousands =  self::convertNumberToWords((int) ($number / 1000)) . ' thousand';
            $remainder = $number % 1000;
            if ($remainder > 0) {
                return $thousands . ' ' .  self::convertNumberToWords($remainder);
            } else {
                return $thousands;
            }
        }

        // Handle even larger numbers
        if ($number < 1000000000) {
            $millions =  self::convertNumberToWords((int) ($number / 1000000)) . ' million';
            $remainder = $number % 1000000;
            if ($remainder > 0) {
                return $millions . ' ' .  self::convertNumberToWords($remainder);
            } else {
                return $millions;
            }
        }

        // Handle numbers larger than or equal to a billion
        return 'number too large to convert';
    }
}
