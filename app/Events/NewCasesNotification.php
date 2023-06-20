<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NewCasesNotification extends Notification
{
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('New Case Created')
            ->icon('/path/to/notification-icon.png')
            ->body('A new case has been created: ' . $notification->case_name)
            ->action('View Case', 'view_case')
            ->data(['url' => '/cases/' . $notification->id]);
    }
}

