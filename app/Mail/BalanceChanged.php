<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class BalanceChanged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The receiver instance.
     *
     * @var User
     */
    public $receiver;
    
    /**
     * Array with the wallets information
     *
     * @var Array
     */
    public $wallets;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $receiver, $wallets)
    {
        $this->receiver = $receiver;
        $this->wallets = $wallets;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
                ->to($this->receiver->email)
                ->markdown('emails.balance_changed')
                ->with([
                    'wallets' => $this->wallets
                ]);
    }
}
