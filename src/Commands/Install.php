<?php

namespace Shura\Asset\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Shura\Invoice\Database\Seeds\TermSeeder;

class Install extends Command
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
        $this->info('Run migration for organization package at folder ');
        $this->info(__DIR__.'/../database/migrations');
        collect(File::allFiles(__DIR__.'/../database/migrations'))->map(function($file){
            Artisan::call("migrate",['--path'=>$file->getPathName(),'--realpath'=>true]);
            return $file->getPathName();
        })->toArray();
        $this->info('Run db sheed for \Shura\Asset\Database\Seeds\AssetTypeSeeder');
        Artisan::call("db:seed",['--class'=>\Shura\Asset\Database\Seeds\AssetTypeSeeder::class]);
        $this->info('Run db sheed for \Shura\Asset\Database\Seeds\StaticDataSeeder');
        Artisan::call("db:seed",['--class'=>\Shura\Asset\Database\Seeds\StaticDataSeeder::class]);
        $this->info('Run permission seeder');
        Artisan::call("db:seed",['--class'=>\Shura\Asset\Database\Seeds\PermissionsSeeder::class]);
    }
}
