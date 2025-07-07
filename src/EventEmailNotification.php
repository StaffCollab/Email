<?php

namespace StaffCollab\Email;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Blade;

class EventEmailNotification extends Notification
{
    protected \StaffCollab\Email\Emailable $event;

    protected \StaffCollab\Email\EmailTemplate $template;

    public function __construct(\StaffCollab\Email\Emailable $event, \StaffCollab\Email\EmailTemplate $template)
    {
        $this->event = $event;
        $this->template = $template;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->when($this->template['from_name'], function ($mail) {
                return $mail->from(null, $this->template['from_name']);
            })
            ->when($this->template['reply_to'], function ($mail) {
                return $mail->replyTo($this->template['reply_to']);
            })
            ->subject(Blade::render($this->template->subject ?? $this->event->getName(), $this->event->getTemplateData()))
            ->greeting(Blade::render($this->template['greeting'], $this->event->getTemplateData()))
            ->lineIf($this->template['body'], Blade::render($this->template['body'], $this->event->getTemplateData()))
            ->when($this->template['call_to_action'] && $this->template['call_to_action_url'], function ($mail) {
                return $mail->action(
                    Blade::render($this->template['call_to_action'], $this->event->getTemplateData()),
                    Blade::render($this->template['call_to_action_url'], $this->event->getTemplateData())
                );
            })
            ->salutation($this->template['signature'], Blade::render($this->template['signature'], $this->event->getTemplateData()));
    }
}
