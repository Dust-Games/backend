<?php

namespace App\Console\Commands;

use App\Models\OAuthAccount;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TempImport as Temp;

class TempImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Some import';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $collection = Excel::toCollection(new Temp(), storage_path('twitchakkaunty.xlsx'));
        foreach ($collection->get(0) as $value) {
            $name = $value->get(0);
            $account = OAuthAccount::query()
                ->where('oauth_provider_id','=', 2)
                ->whereAccountId($name)->first();
            if ($account) {
                $account->update(['account_id' => $value->get(1), 'username' => $name]);
            }
        };
    }
}
