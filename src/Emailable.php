<?php

namespace StaffCollab\Email;

interface Emailable
{
    /**
     * Get the recipients as an array of model instances.
     *
     * @return array<\Illuminate\Database\Eloquent\Model>
     */
    public static function getRecipients(): array;

    /**
     * Get the event name (for email context).
     */
    public static function getName(): string;

    /**
     * Get the attachments for the email.
     */
    public static function getAttachments(): array;

    /**
     * Get the data to pass to the email template.
     */
    public static function getTemplateData(): array;
}
