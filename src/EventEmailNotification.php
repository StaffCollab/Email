<?php

namespace StaffCollab\Email;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventEmailNotification extends Notification
{
    protected Emailable $event;

    protected string $fromName;

    protected string $replyTo;

    protected string $subject;

    protected string $greeting;

    protected string $body;

    protected string $callToActionUrl;

    protected string $callToActionText;

    protected string $signature;

    public function __construct(Emailable $event, EmailTemplate $template)
    {
        $this->event = $event;
        $this->fromName = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->from_name);
        $this->replyTo = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->reply_to);
        $this->subject = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->subject);
        $this->greeting = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->greeting);
        $this->body = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->body);
        $this->callToActionText = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->call_to_action);
        $this->callToActionUrl = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->call_to_action_url);
        $this->signature = tiptap_converter()->mergeTagsMap($event->getTemplateData())->asHTML($template->signature);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->when($this->fromName, function ($mail) {
                return $mail->from(null, $this->fromName);
            })
            ->when($this->replyTo, function ($mail) {
                return $mail->replyTo($this->replyTo);
            })
            ->subject($this->subject)
            ->greeting($this->greeting)
            ->lineIf($this->body, $this->body)
            ->when($this->callToActionText && $this->callToActionUrl, function ($mail) {
                return $mail->action($this->callToActionText, $this->callToActionUrl);
            })
            ->salutation($this->signature, $this->signature);
    }
}
