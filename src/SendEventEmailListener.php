<?php

namespace StaffCollab\Email;

use Illuminate\Support\Facades\Log;

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

        Log::debug('Event: ' . get_class($event));

        $templates = EmailTemplate::where('event_class', class_basename($eventName))->get();

        if ($templates->isEmpty()) {
            Log::debug('No email templates found for event: ' . get_class($event));

            return;
        }

        foreach ($templates as $template) {
            Log::info("execute template " . $template->id);
            $recipientKeys = $template->recipient_keys ?? [];
            $attachmentKeys = $template->attachment_keys ?? [];
            $recipients = collect($event->getRecipients())
                ->filter(function ($recipient, $key) use ($recipientKeys) {
                    return in_array($key, $recipientKeys);
                });
            if ($recipients->isEmpty()) {
                Log::info('No recipients found for template: ' . $template->id);
                continue;
            }
            foreach ($recipients as $recipient) {
                Log::critical('Sending email to: ' . $recipient->email);
                $recipient->notify(new EventEmailNotification($event, $template));
            }
        }
    }
}
