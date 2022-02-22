<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindOfPremiumExpiration extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $daysLeft, $username, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($daysLeft, $username)
    {
        $this->daysLeft = $daysLeft;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $multipleDays = $this->daysLeft > 1 ? "s" : "";
        $this->message = $this->daysLeft > 1 ?
            "Your SelfAccounting Premium will expire in **$this->daysLeft days**" :
            "Your SelfAccounting Premium will expire **by the end of the day**";

        return $this
            ->subject(
                $this->daysLeft > 1 ?
                "Only $this->daysLeft day$multipleDays of SelfAccounting Premium left!" :
                "Today is the last day of your SelfAccounting Premium!"
            )
            ->markdown("emails.remind-of-premium-expiration");
    }
}
