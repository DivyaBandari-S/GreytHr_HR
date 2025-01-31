<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskAssignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $taskName;
    public $description;
    public $dueDate;
    public $priority;
    public $assignedBy;
    public $searchData;
    public $formattedAssignee;
    public $formattedFollowerName;
    public $isFollower;

    public function __construct($taskName, $description, $dueDate, $priority, $assignedBy, $formattedAssignee, $searchData = [], $formattedFollowerName, $isFollower)
    {

        $this->taskName = $taskName;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->priority = $priority;
        $this->assignedBy = $assignedBy;
        $this->searchData = $searchData;
        $this->formattedAssignee = $formattedAssignee;
        $this->formattedFollowerName = $formattedFollowerName;
        $this->isFollower = $isFollower;
    }

    public function build()
    {
        return $this->subject('New Task Assigned: ')
            ->view('mails.task_assigned_notification') // Ensure this Blade view exists
            ->with([
                'searchData' => $this->searchData ?? [],
                'formattedAssignee' => $this->formattedAssignee,
                'taskName' => $this->taskName,
                'description' => $this->description,
                'priority' => $this->priority,
                'assignedBy' => $this->assignedBy,
                'dueDate' => $this->dueDate,
                'formattedFollowerName' => $this->formattedFollowerName,
                'isFollower' => $this->isFollower,
            ]);
    }

    public function envelope(): Envelope
    {
        $subject = $this->isFollower ? 'Task Update Notification' : 'Task Assigned Notification';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.task_assigned_notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
