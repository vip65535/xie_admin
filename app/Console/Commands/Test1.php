<?php

namespace App\Console\Commands;

use App\Utils\SocketPOPClient;
use Illuminate\Console\Command;

class Test1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xiely:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $pop =  new SocketPOPClient('',"", 'pop3.126.com', '110');
        if ($pop->popLogin()) {
            $r = true;
        }else{
            $r = false;
        }
        var_dump($r);
        $pop->closeHost();
    }
}
