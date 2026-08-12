<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Notifications;

use App\Models\Ticket;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket, public string $eventType)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        // Role master switch only gates in-app (database) notifications.
        if (! ($notifiable instanceof \App\Models\User) || $notifiable->inAppNotificationsEnabled()) {
            $channels[] = 'database';
        }

        // Email / SMS still follow per-user preferences.
        if ($this->getContent($notifiable)) {
            if ($notifiable->notification_email) {
                $channels[] = 'mail';
            }
            if ($notifiable->notification_sms) {
                $channels[] = SmsChannel::class;
            }
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): ?MailMessage
    {
        $content = $this->getContent($notifiable);

        if (!$content) {
            return null;
        }

        $currentUserRole = $notifiable->portalRoutePrefix();
        $subject = $this->getSubject();
        $actionText = 'View Ticket';

        return (new MailMessage)
            ->subject($subject)
            ->line($content)
            ->action($actionText, route("{$currentUserRole}.tickets.show", $this->ticket->id))
            ->line('Thank you for using our application!');
    }

    public function toSms(Object $notifiable)
    {

        $currentUserRole = $notifiable->portalRoutePrefix();
        $content = $this->getContent($notifiable). ' ' . route("{$currentUserRole}.tickets.show", $this->ticket->id);
        return $content;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $currentUserRole = $notifiable->portalRoutePrefix();

        return [
            'id' => $this->ticket->id,
            'title' => $this->getSubject(),
            'content' => $this->getContent($notifiable),
            'event' => $this->eventType,
            'url' => route("{$currentUserRole}.tickets.show", $this->ticket->id),
        ];
    }

    /**
     * Get the content for the notification.
     */
    protected function getContent($notifiable): ?string
    {
        $content = '';
        switch ($this->eventType) {
            case 'created':
                if ($notifiable->isInternalAdmin()) {
                    $content = "A new ticket has been submitted by ".$this->ticket->driver?->user?->name ?? $this->ticket->name.". Ticket ID: #{$this->ticket->id}.";
                } elseif ($notifiable->hasRole('driver')) {
                    $content = "Your ticket has been created successfully. Ticket ID: #{$this->ticket->id}.";
                }
                break;

            case 'assigned':
                if ($notifiable->isInternalAdmin()) {
                    $content = "Ticket ID #{$this->ticket->id} has been assigned to {$this->ticket->attorney->user->name}.";
                } elseif ($notifiable->hasRole('driver')) {
                    $content = "Your ticket has been assigned to {$this->ticket->attorney->user->name}.";
                }
                break;

            case 'status_updated':
                $status = $this->ticket->indicator;
                if ($notifiable->hasRole('driver')) {
                    $content = "Ticket ID #{$this->ticket->id} is now {$status}.";
                } elseif ($notifiable->isInternalAdmin()) {
                    $content = "Ticket ID #{$this->ticket->id} has been changed to {$status}.";
                }
                break;

            case 'response':
                if ($notifiable->hasRole('driver')) {
                    $content = "An update has been added to your ticket ID #{$this->ticket->id}. Log in to view.";
                }
                break;

            case 'document_uploaded':
                if ($notifiable->isInternalAdmin()) {
                    $content = "A new document has been uploaded to Ticket ID #{$this->ticket->id} by {$this->ticket->driver?->name}.";
                } elseif ($notifiable->hasRole('driver')) {
                    $content = "Your uploaded document for Ticket ID #{$this->ticket->id} has been received.";
                }
                break;

            case 'resolved':
                if ($notifiable->hasRole('driver')) {
                    $content = "Your ticket ID #{$this->ticket->id} has been resolved. Contact us for any further concerns.";
                }
                break;

            case 'approved':
                if ($notifiable->hasRole('driver')) {
                    $created_at = \Carbon\Carbon::parse($this->ticket->created_at)->diffForHumans();
                    $content = "your ticket  #{$this->ticket->id} submitted on {$created_at} has been reviewed by an administrator and advanced to the next step.";
                }
                break;

            case 'updated':
                if ($notifiable->hasRole('driver')) {
                    $content = "Your ticket #{$this->ticket->id} has been updated successfully.";
                } elseif ($notifiable->isInternalAdmin()) {
                    $content = "Ticket ID #{$this->ticket->id} has been updated.";
                }
                break;
        }

        return $content ?: null;
    }

    /**
     * Get the subject for the notification.
     */
    protected function getSubject(): string
    {
        return match ($this->eventType) {
            'created' => "New Ticket #{$this->ticket->id} Created",
            'assigned' => "Ticket #{$this->ticket->id} Assigned",
            'status_updated' => "Ticket #{$this->ticket->id} Status Updated",
            'response' => "Update on Ticket #{$this->ticket->id}",
            'document_uploaded' => "New Document Uploaded for Ticket #{$this->ticket->id}",
            'resolved' => "Ticket #{$this->ticket->id} Resolved",
            'approved' => "Ticket #{$this->ticket->id} submitted successfully!",
            'updated' => "Ticket #{$this->ticket->id} Updated",
            default => "Notification for Ticket #{$this->ticket->id}",
        };
    }
}
