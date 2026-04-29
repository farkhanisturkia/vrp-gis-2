<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $notification;

    public function __construct(Order $order, $notification)
    {
        $this->order = $order;
        $this->notification = $notification;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Status Order #' . $this->order->id . ' [' . strtoupper($this->notification->status) . ']',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_notification',
        );
    }
}