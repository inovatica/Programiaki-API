<?php

namespace App\Jobs;

use Mail;
use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $user \App\Models\User
     */
    protected $user;

    /**
     * @var $token string
     */
    protected $token;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new EmailVerification($this->user, $this->token);
        Mail::to($this->user->email)->send($email);
    }
}
