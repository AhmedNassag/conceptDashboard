<?php

namespace App\Notifications\Admin;

use App\Models\SuggestionUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AddNotification extends Notification
{
    use Queueable;
    public $data;
    public $name;
    public $page_id;
    public $message;
    public $image;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data = '',$page_id = null,$name = '',$message='',$image='')
    {
        $this->data = $data;
        $this->name = $name;
        $this->page_id = $page_id;
        $this->message = $message;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'timeDate' => now()->format('jS \of F  h:i'),
            'name' => $this->name,
            'id' => $this->page_id,
            'message' => $this->message,
            'image' => $this->image,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'timeDate' => now()->format('jS \of F  h:i'),
                'name' => $this->name,
                'id' => $this->page_id,
                'message' => $this->message,
                'image' => $this->image,
            ]
        ]);
    }
}
