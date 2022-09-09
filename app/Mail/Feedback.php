<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to(env('MAIL_FROM_ADDRESS'))
            ->subject('Заявка с сайта: {source: website} {source_category: '. $_SERVER['HTTP_HOST'] .'}')
            ->view('web.mail.feedback', ['data' => $this->data]);
    }
}
