<?php

namespace App\Models;

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
        return $value ? $this->decodeCTC($value) : 0;
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

        return $integerValue / pow(10, $decimalPlaces);
    }

    /**
     * Relationship to Employee model.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
