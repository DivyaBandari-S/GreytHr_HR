<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayrollProcessedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */ public $employee;
    public $selectedMonth;

    /**
     * Create a new message instance.
     */
    public function __construct($employee, $selectedMonth)
    {
        $this->employee = $employee;
        $this->selectedMonth = $selectedMonth;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Payslip released for " . \Carbon\Carbon::parse($this->selectedMonth . '-01')->translatedFormat('F Y'))

            ->view('emails.payroll_processed');
    }
}
