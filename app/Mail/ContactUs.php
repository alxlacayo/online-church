<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The email address.
     *
     * @var String
     */
    public $email;

    /**
     * The name.
     *
     * @var String
     */
    public $name;

    /**
     * The body.
     *
     * @var String
     */
    public $body;

    /**
     * Create a new body instance.
     *
     * @return void
     */
    public function __construct($email, $name, $body)
    {
        $this->email = $email;
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * Build the body.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@second.org')
            ->text('emails.contact')
            ->with([
                'email' => $this->email,
                'name' => $this->name,
                'body' => $this->body
            ]);
    }
}
