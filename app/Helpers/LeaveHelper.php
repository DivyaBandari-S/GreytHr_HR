<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\LeaveRequest;

class LeaveHelper
{
    public static function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Check if the start or end date is a weekend
            if ($startDate->isWeekend() || $endDate->isWeekend()) {
                return 'Error: Selected dates fall on a weekend. Please choose weekdays.';
            }

            if ($startDate->isSameDay($endDate)) {
                if (self::getSessionNumber($fromSession) !== self::getSessionNumber($toSession)) {

                    return 1;
                } elseif (self::getSessionNumber($fromSession) == self::getSessionNumber($toSession)) {
                    return 0.5;
                } else {
                    return 0;
                }
            }

            $totalDays = 0;

            while ($startDate->lte($endDate)) {
                if ($leaveType == 'Sick Leave') {
                    $totalDays += 1;
                } else {
                    if ($startDate->isWeekday()) {
                        $totalDays += 1;
                    }
                }
                // Move to the next day
                $startDate->addDay();
            }

            // Deduct weekends based on the session numbers
            if (self::getSessionNumber($fromSession) > 1) {
                $totalDays -= self::getSessionNumber($fromSession) - 1; // Deduct days for the starting session
            }
            if (self::getSessionNumber($toSession) < 2) {
                $totalDays -= 2 - self::getSessionNumber($toSession); // Deduct days for the ending session
            }
            // Adjust for half days
            if (self::getSessionNumber($fromSession) === self::getSessionNumber($toSession)) {
                // If start and end sessions are the same, check if the session is not 1
                if (self::getSessionNumber($fromSession) !== 1) {
                    $totalDays += 0.5; // Add half a day
                } else {
                    $totalDays += 0.5;
                }
            } elseif (self::getSessionNumber($fromSession) !== self::getSessionNumber($toSession)) {
                if (self::getSessionNumber($fromSession) !== 1) {
                    $totalDays += 1; // Add half a day
                }
            } else {
                $totalDays += (self::getSessionNumber($toSession) - self::getSessionNumber($fromSession) + 1) * 0.5;
            }

            return $totalDays;
        } catch (\Exception $e) {
            // FlashMessageHelper::flashError('An error occured while calculating no. of days.');
            return false;
        }
    }

    private static function getSessionNumber($session)
    {
        // You might need to customize this based on your actual session values
        return (int) str_replace('Session ', '', $session);
    }

    public static function getApprovedLeaveDays($employeeId, $selectedYear)
    {
        try {
            // Fetch approved leave requests
            $selectedYear = (int) $selectedYear;
            $approvedLeaveRequests = LeaveRequest::where('emp_id', $employeeId)
                ->where('category_type', 'Leave')
                ->where(function ($query) {
                    $query->where('leave_status', 2)
                        ->whereIn('cancel_status', [6, 5, 3, 4]);
                })
                ->whereIn('leave_type', [
                    'Casual Leave Probation',
                    'Loss Of Pay',
                    'Sick Leave',
                    'Casual Leave',
                    'Maternity Leave',
                    'Marriage Leave',
                    'Paternity Leave',
                    'Earned Leave'
                ])
                ->whereYear('to_date', '=', $selectedYear)
                ->get();
            $totalCasualDays = 0;
            $totalCasualLeaveProbationDays = 0;
            $totalSickDays = 0;
            $totalLossOfPayDays = 0;
            $totalMaternityDays = 0;
            $totalMarriageDays = 0;
            $totalPaternityDays = 0;
            $totalEarnedDays = 0;

            // Calculate the total number of days based on sessions for each approved leave request
            foreach ($approvedLeaveRequests as $leaveRequest) {
                $leaveType = $leaveRequest->leave_type;
                $days = self::calculateNumberOfDays(
                    $leaveRequest->from_date,
                    $leaveRequest->from_session,
                    $leaveRequest->to_date,
                    $leaveRequest->to_session,
                    $leaveRequest->leave_type
                );

                // Accumulate days based on leave type
                switch ($leaveType) {
                    case 'Casual Leave':
                        $totalCasualDays += $days;
                        break;
                    case 'Sick Leave':
                        $totalSickDays += $days;
                        break;
                    case 'Loss Of Pay':
                        $totalLossOfPayDays += (int) $days;
                        break;
                    case 'Casual Leave Probation':
                        $totalCasualLeaveProbationDays += $days;
                        break;
                    case 'Maternity Leave':
                        $totalMaternityDays += $days;
                        break;
                    case 'Marriage Leave':
                        $totalMarriageDays += $days;
                        break;
                    case 'Paternity Leave': // Corrected the spelling
                        $totalPaternityDays += $days;
                        break;
                    case 'Earned Leave': // Corrected the spelling
                        $totalEarnedDays += $days;
                        break;
                }
            }
            return [
                'totalCasualDays' => $totalCasualDays,
                'totalCasualLeaveProbationDays' => $totalCasualLeaveProbationDays,
                'totalSickDays' => $totalSickDays,
                'totalLossOfPayDays' => $totalLossOfPayDays,
                'totalMaternityDays' => $totalMaternityDays,
                'totalMarriageDays' => $totalMarriageDays,
                'totalPaternityDays' => $totalPaternityDays,
                'totalEarnedDays' => $totalEarnedDays
            ];
        } catch (\Exception $e) {
            // Log the error message or handle it as needed
            // FlashMessageHelper::flashError('An error occurred while fetching leave days. Please try again later.');
            return null; // Return null or an empty array to indicate failure
        }
    }

    public static function getApprovedLeaveDaysOnSelectedDay($employeeId, $selectedYear)
    {
        // Fetch approved leave requests
        $approvedLeaveRequests = LeaveRequest::where('emp_id', $employeeId)
            ->where(function ($query) {
                $query->where('leave_status', 2)
                    ->whereIn('cancel_status', [6, 5, 3, 4]); // Check both 'leave_status' and 'cancel_status'
            })
            ->whereIn('leave_type', [
                'Casual Leave Probation',
                'Loss Of Pay',
                'Sick Leave',
                'Casual Leave',
                'Maternity Leave',
                'Marriage Leave',
                'Paternity Leave'
            ])
            ->whereYear('to_date', '=', $selectedYear)
            ->get();


        $totalCasualDays = 0;
        $totalCasualLeaveProbationDays = 0;
        $totalSickDays = 0;
        $totalLossOfPayDays = 0;
        $totalMaternityDays = 0;
        $totalMarriageDays = 0;
        $totalPaternityDays = 0;

        // Calculate the total number of days based on sessions for each approved leave request
        foreach ($approvedLeaveRequests as $leaveRequest) {
            $leaveType = $leaveRequest->leave_type;
            $days = self::calculateNumberOfDays(
                $leaveRequest->from_date,
                $leaveRequest->from_session,
                $leaveRequest->to_date,
                $leaveRequest->to_session,
                $leaveRequest->leave_type
            );

            // Accumulate days based on leave type
            switch ($leaveType) {
                case 'Casual Leave':
                    $totalCasualDays += $days;
                    break;
                case 'Sick Leave':
                    $totalSickDays += $days;
                    break;
                case 'Loss Of Pay':
                    $totalLossOfPayDays += $days;
                    break;
                case 'Casual Leave Probation':
                    $totalCasualLeaveProbationDays += $days;
                    break;
                case 'Maternity Leave':
                    $totalMaternityDays += $days;
                    break;
                case 'Marriage Leave':
                    $totalMarriageDays += $days;
                    break;
                case 'Petarnity Leave':
                    $totalPaternityDays += $days;
                    break;
            }
        }
        return [
            'totalCasualDays' => $totalCasualDays,
            'totalCasualLeaveProbationDays' => $totalCasualLeaveProbationDays,
            'totalSickDays' => $totalSickDays,
            'totalLossOfPayDays' => $totalLossOfPayDays,
            'totalMaternityDays' => $totalMaternityDays,
            'totalMarriageDays' => $totalMarriageDays,
            'totalPaternityDays' => $totalPaternityDays,
        ];
    }
}
