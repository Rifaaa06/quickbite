<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompleted extends Notification implements ShouldQueue
{
  use Queueable;

  protected $order;

  /**
   * Create a new notification instance.
   */
  public function __construct(Order $order)
  {
      $this->order = $order;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
      return ['mail', 'database'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {
      return (new MailMessage)
          ->subject('Your Order is Completed')
          ->greeting('Hello ' . $notifiable->name . '!')
          ->line('Your order #' . $this->order->order_number . ' from ' . $this->order->canteen->name . ' has been completed.')
          ->line('We hope you enjoyed your meal!')
          ->line('Thank you for using our campus canteen system!')
          ->action('View Order Details', route('buyer.orders.show', $this->order));
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
      return [
          'order_id' => $this->order->id,
          'order_number' => $this->order->order_number,
          'message' => 'Your order #' . $this->order->order_number . ' has been completed.',
      ];
  }
}

