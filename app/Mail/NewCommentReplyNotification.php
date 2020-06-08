<?php

namespace App\Mail;

use App\Comment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentReplyNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
      $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $this->enquiry->....
        return $this->subject('Someone’s replied to you!')->markdown('emails.newcommentreply');
    }
}
