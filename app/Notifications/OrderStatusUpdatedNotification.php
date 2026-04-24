<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusMessages = [
            'confirmed' => "Your order {$this->order->order_number} has been confirmed!",
            'preparing' => "Your order {$this->order->order_number} is now being prepared.",
            'ready' => "Your order {$this->order->order_number} is ready for pickup!",
            'out_for_delivery' => "Your order {$this->order->order_number} is out for delivery!",
            'completed' => "Your order {$this->order->order_number} has been completed. Thank you!",
            'cancelled' => "Your order {$this->order->order_number} has been cancelled.",
        ];

        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->order->status,
            'message' => $statusMessages[$this->order->status] ?? "Your order {$this->order->order_number} status changed to {$this->order->status}.",
        ];
    }
}
