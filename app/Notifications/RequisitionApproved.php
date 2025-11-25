<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionApproved extends Notification
{
    use Queueable;

    public $requisition;

    /**
     * Create a new notification instance.
     */
    public function __construct(Requisition $requisition)
    {
        $this->requisition = $requisition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Requisition #' . $this->requisition->req_id . ' Approved')
            ->line('Your requisition has been approved.')
            ->line('Requisition ID: #' . $this->requisition->req_id)
            ->line('Product: ' . $this->requisition->product->name)
            ->line('Quantity: ' . $this->requisition->quantity)
            ->line('Description: ' . $this->requisition->description)
            ->action('View Requisition', url('/employee/requisitions/' . $this->requisition->req_id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'requisition_id' => $this->requisition->req_id,
            'message' => 'Your requisition #' . $this->requisition->req_id . ' has been approved.',
            'url' => '/employee/requisitions/' . $this->requisition->req_id,
        ];
    }
}
