<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $daysSince, $username;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($daysSince, $username)
    {
        $this->daysSince = $daysSince;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("It's been $this->daysSince days since your last visit to SelfAccounting!")
            ->markdown("emails.activity-reminder");
    }
}
