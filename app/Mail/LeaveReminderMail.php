<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $leaveRequest;
    /**
     * Create a new message instance.
     */
    public function __construct( $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }
    public function build()
    {
        $employeeName = $this->leaveRequest->employee->first_name . ' ' . $this->leaveRequest->employee->last_name;
        return $this->subject('Leave Approval Reminder')
            ->view('emails.leave_reminder')
            ->with([
                'employeeName' => $employeeName,
                'leaveRequest' => $this->leaveRequest,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Leave Reminder Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.leave_remainder_mail',
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
}
