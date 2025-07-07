<?php

namespace StaffCollab\Email\Commands;

use Illuminate\Console\Command;

class EmailCommand extends Command
{
    public $signature = 'email';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
