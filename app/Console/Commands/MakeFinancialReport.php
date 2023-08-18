<?php

namespace App\Console\Commands;

use App\Http\Controllers\FinancialController;
use Illuminate\Console\Command;

class MakeFinancialReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-financial-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $financial = (new FinancialController)->store();
    }
}
