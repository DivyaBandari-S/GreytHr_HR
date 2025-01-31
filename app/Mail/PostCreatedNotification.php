<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;



class PostCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $employeeDetails;
    public $managerName;
    
    public function __construct($post, $employeeDetails, $managerName)
    {
        $this->post = $post;
        $this->employeeDetails = $employeeDetails;
        $this->managerName = $managerName;
    }
    


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Post created by ' . $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name
        );
    }
    
    public function build()
    {
        Log::info('Building email with post:', ['post' => $this->post]);
        return $this->subject('New Post Notification')
                    ->view('emails.post_created');
    }
    

    /**
     * Get the message content definition.
     */

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
