<?php

namespace StaffCollab\Email\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StaffCollab\Email\Email
 */
class Email extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \StaffCollab\Email\Email::class;
    }
}
