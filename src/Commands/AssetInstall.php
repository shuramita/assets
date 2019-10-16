<?php

namespace Shura\Asset\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Shura\Invoice\Database\Seeds\TermSeeder;

class AssetInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:install';

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
        Artisan::call("migrate");
//        $this->call([
//            \Shura\Asset\Database\Seeds\AssetTypeSeeder::class
//        ]);
        Artisan::call("db:seed",['--class'=> "Shura\Asset\Database\Seeds\AssetTypeSeeder"]);
        //php artisan db:seed --class="Shura\Asset\Database\Seeds\StaticDataSeeder"
        Artisan::call("db:seed",['--class'=> "Shura\Asset\Database\Seeds\StaticDataSeeder"]);



    }
}