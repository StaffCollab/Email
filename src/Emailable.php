<?php

namespace StaffCollab\Email;

interface Emailable
{
    public function getRecipients(): array;

    public function getAttachments(): array;

    public function getTemplateData(): array;

    public static function getName(): string;

    public static function getRecipientOptions(): array;

    public static function getAttachmentOptions(): array;

    public static function getMergeTags(): array;
}
