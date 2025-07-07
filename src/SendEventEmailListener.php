<?php

namespace StaffCollab\Email;

class SendEventEmailListener
{
    public function __construct()
    {
        //
    }

    public function handle($eventName, $payload): void
    {
        $event = $payload[0] ?? null;

        if (! $payload[0] || ! $payload[0] instanceof Emailable) {
            return; // Ensure the event implements the Emailable interface
        }

        $templates = EmailTemplate::where('event_class', class_basename($eventName))->get();

        if ($templates->isEmpty()) {
            return;
        }

        foreach ($templates as $template) {
            $recipientKeys = $template->recipient_keys ?? [];
            $attachmentKeys = $template->attachment_keys ?? [];
            $recipients = collect($event::getRecipients())
                ->filter(function ($recipient, $key) use ($recipientKeys) {
                    return in_array($key, $recipientKeys);
                });
            foreach ($recipients as $recipient) {
                $recipient->notify(new EventEmailNotification($event, $template));
            }
        }
    }
}
