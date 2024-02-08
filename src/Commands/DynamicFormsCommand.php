<?php

namespace JibayMcs\DynamicForms\Commands;

use Illuminate\Console\Command;

class DynamicFormsCommand extends Command
{
    public $signature = 'dynamic-forms';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
