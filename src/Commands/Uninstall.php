<?php

namespace Shura\Asset\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class Uninstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command uninstall database';

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
        if($this->ask('Enter Password to uninstall asset database') !== 'asset'){
            $this->line('Password incorrect!');
            return;
        };
        // delete all tables
        Schema::dropIfExists('ass_building');
        Schema::dropIfExists('ass_assets');
        Schema::dropIfExists('ass_mediables');
        Schema::dropIfExists('ass_type');
        Schema::dropIfExists('ass_floor');
        Schema::dropIfExists('ass_asset_price');
        Schema::dropIfExists('ass_price');
        Schema::dropIfExists('ass_organization');
        Schema::dropIfExists('ass_setting');
        Schema::dropIfExists('ass_permission');
        Schema::dropIfExists('ass_user_role');
        Schema::dropIfExists('ass_role');

        // Venue module, later move this to new module, Vuenue Module
//        Schema::dropIfExists('ven_venue');
        Schema::dropIfExists('ass_static_data');
        Schema::dropIfExists('ass_asset_static_data');
        Schema::dropIfExists('ass_booking');
        Schema::dropIfExists('ass_customer');
        Schema::dropIfExists('ass_payment');
        Schema::dropIfExists('ass_asset_field');
        Schema::dropIfExists('ass_asset_tag');


        // remove all migrations in migration table
        $migrations = collect(File::allFiles(__DIR__.'/../database/migrations'))->map(function($file){
            return str_replace('.php','',$file->getFilename());
        });
        $sql = DB::table('migrations')->whereIn('migration',$migrations)->delete();
        $this->line($sql);

    }
}
