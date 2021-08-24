<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindOfPremiumExpiration extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $daysLeft, $username;

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
        return $this
            ->subject("Only $this->daysLeft days of SelfAccounting Premium left!")
            ->markdown("emails.remind-of-premium-expiration");
    }
}
