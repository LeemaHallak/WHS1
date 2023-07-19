<?php

namespace App\Console\Commands;

use App\Models\OrderList;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:list';

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
        $OrderList = OrderList::where('ordered', '==', 0);
        $timeCond = $OrderList->value('created_at')->format('d/m/Y');
        $TimeCondC = Carbon::createFromFormat('d/m/Y', $timeCond);
        $Condition = $TimeCondC->addDay();
        $current = Carbon::now();
        $compare = $Condition->lte($current);

        if($compare==true)
        {
            $OrderList->delete();
        }       
    }
}
