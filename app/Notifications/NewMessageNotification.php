<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\User;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Email-first for messaging. SMS only when a company/driver user has SMS enabled.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if (filled($notifiable->email ?? null)) {
            $channels[] = 'mail';
        }

        if (
            $notifiable instanceof User
            && $notifiable->notification_sms
            && $notifiable->hasAnyRole([
                User::ROLE_DRIVER,
                User::ROLE_COMPANY_ADMIN,
                User::ROLE_MANAGER,
            ])
            && filled($notifiable->phone ?? null)
        ) {
            $channels[] = SmsChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        [$senderName, $conversationName, $preview] = $this->messageParts();

        return (new MailMessage)
            ->subject('New message from '.$senderName)
            ->greeting('Hello '.$notifiable->name.',')
            ->line($senderName.' sent you a message in "'.$conversationName.'".')
            ->line($preview)
            ->action('Open Messaging', route('messaging.show', $this->message->conversation_id))
            ->line('Thank you for using '.config('app.name').'!');
    }

    public function toSms(object $notifiable): string
    {
        [$senderName, $conversationName, $preview] = $this->messageParts();

        return sprintf(
            'New message from %s in "%s": %s %s',
            $senderName,
            $conversationName,
            Str::limit($preview, 120),
            route('messaging.show', $this->message->conversation_id)
        );
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    private function messageParts(): array
    {
        $this->message->loadMissing(['sender', 'conversation', 'attachments']);

        $senderName = $this->message->sender?->name ?: 'Someone';
        $conversationName = $this->message->conversation?->name ?: 'a conversation';
        $preview = trim((string) $this->message->content);

        if ($preview === '') {
            $preview = $this->message->attachments->isNotEmpty()
                ? 'Sent an attachment.'
                : 'Sent a new message.';
        } else {
            $preview = Str::limit($preview, 200);
        }

        return [$senderName, $conversationName, $preview];
    }
}
