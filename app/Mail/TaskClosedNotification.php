<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TaskClosedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $taskName;
    public $description;
    public $dueDate;
    public $priority;
    public $assignedBy;
    public $formattedAssignee;

    public function __construct($taskName, $description, $dueDate, $priority, $assignedBy, $formattedAssignee)
    {
        $this->taskName = $taskName;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->priority = $priority;
        $this->assignedBy = $assignedBy;
        $this->formattedAssignee = $formattedAssignee;
    }

    public function build()
    {
        return $this->subject('Task Closed Notification: ' . $this->taskName)
                    ->view('emails.task_closed')  // Ensure you have a 'task_closed.blade.php' view file
                    ->with([
                        'taskName' => $this->taskName,
                        'description' => $this->description,
                        'dueDate' => $this->dueDate,
                        'priority' => $this->priority,
                        'assignedBy' => $this->assignedBy,
                        'formattedAssignee' => $this->formattedAssignee,
                    ]);
    }
    public function envelope(): Envelope
    {
        $subject = 'Task Closed Notification';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.task_closed_notification',
        );
    }
}
