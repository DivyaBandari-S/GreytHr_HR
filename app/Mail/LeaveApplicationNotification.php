<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EmployeeDetails;
use Carbon\Carbon;

class LeaveApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $leaveRequest;
    public $employeeDetails;
    public $applyingToDetails;
    public $ccToDetails;
    public $cancelStatus;
    public $leaveCategory;
    public function __construct($leaveRequest, $applyingToDetails, $ccToDetails)
    {
        $this->leaveRequest = $leaveRequest;
        $this->applyingToDetails = $applyingToDetails;
        $this->ccToDetails = $ccToDetails;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $leaveRequest->emp_id)->first();
    }
    public function build()
    {
        // Calculate number of days before passing to the view
        $numberOfDays = $this->calculateNumberOfDays(
            $this->leaveRequest->from_date,
            $this->leaveRequest->from_session,
            $this->leaveRequest->to_date,
            $this->leaveRequest->to_session,
            $this->leaveRequest->leave_type
        );

        return $this->view('mails.leave_application_notification')
            ->with([
                'leaveRequest' => $this->leaveRequest,
                'applyingToDetails' => $this->applyingToDetails,
                'ccToDetails' => $this->ccToDetails,
                'employeeDetails' => $this->employeeDetails,
                'numberOfDays' => $numberOfDays,
                'leave_status' => $this->leaveRequest->leave_status,
                'leaveCategory' => $this->leaveRequest->category_type,
                'cancelStatus' => $this->leaveRequest->cancel_status,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '';
        if ($this->leaveRequest->category_type === 'Leave'){
            if ($this->leaveRequest->leave_status === 4) {
                $subject = 'Leave Application from: ' . ucwords(strtolower($this->employeeDetails->first_name)) . ' ' . ucwords(strtolower($this->employeeDetails->last_name)) . ' (' . $this->employeeDetails->emp_id . ') has been withdrawn.';
            } else {
                $subject = 'Leave Application from: ' . ucwords(strtolower($this->employeeDetails->first_name)) . ' ' . ucwords(strtolower($this->employeeDetails->last_name)) . ' (' . $this->employeeDetails->emp_id . ')';
            }
        }else{
            if ($this->leaveRequest->cancel_status === 4) {
                $subject = 'Leave Cancel Application from: ' . ucwords(strtolower($this->employeeDetails->first_name)) . ' ' . ucwords(strtolower($this->employeeDetails->last_name)) . ' (' . $this->employeeDetails->emp_id . ') has been withdrawn.';
            } else {
                $subject = 'Leave Cancel Application from: ' . ucwords(strtolower($this->employeeDetails->first_name)) . ' ' . ucwords(strtolower($this->employeeDetails->last_name)) . ' (' . $this->employeeDetails->emp_id . ')';
            }
        }

        return new Envelope(
            subject: $subject // Use the subject variable here
        );
    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.leave_application_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Check if the start or end date is a weekend
            if ($startDate->isWeekend() || $endDate->isWeekend()) {
                return 0;
            }

            // Check if the start and end sessions are different on the same day
            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays
                if (!$startDate->isWeekend() && !$endDate->isWeekend()) {
                    return 0.5;
                } else {
                    // If either start or end date is a weekend, return 0
                    return 0;
                }
            }

            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays
                if (!$startDate->isWeekend() && !$endDate->isWeekend()) {
                    return 1;
                } else {
                    // If either start or end date is a weekend, return 0
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
            if ($this->getSessionNumber($fromSession) > 1) {
                $totalDays -= $this->getSessionNumber($fromSession) - 1; // Deduct days for the starting session
            }
            if ($this->getSessionNumber($toSession) < 2) {
                $totalDays -= 2 - $this->getSessionNumber($toSession); // Deduct days for the ending session
            }
            // Adjust for half days
            if ($this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)) {
                // If start and end sessions are the same, check if the session is not 1
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 0.5; // Add half a day
                } else {
                    $totalDays += 0.5;
                }
            } elseif ($this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 1; // Add half a day
                }
            } else {
                $totalDays += ($this->getSessionNumber($toSession) - $this->getSessionNumber($fromSession) + 1) * 0.5;
            }

            return $totalDays;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function getSessionNumber($session)
    {
        return (int) str_replace('Session ', '', $session);
    }
}
