<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var $token string
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @param $user User
     * @param $token string
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $confirmation_link = route('register.confirm', $this->token);

        return $this->markdown('emails.auth.email-verification')
            ->with('confirmation_link', $confirmation_link);
    }
}
