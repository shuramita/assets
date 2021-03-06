<?php

namespace Shura\Asset\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command install database';

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
        $this->call("asset:uninstall");
        $this->call("asset:install");
    }
}
