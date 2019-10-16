<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Role;
use Shura\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Shura\Asset\Helpers\ImperialUnit;
//phpunit --filter ValidateImperialUnit

class ValidateImperialUnit extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_constructor()
    {
        $size = new ImperialUnit('34243.43 sqft');
        $this->assertEquals($size->unit,'sqft');
        $size = new ImperialUnit('4,234.32 dfsf , .');
        $this->assertEquals($size->unit,'sqft');
        $this->assertEquals($size->note,'The format is not valid, the defaut unit fallback to sqft');
        $this->assertEquals($size->value, 4234.32);
        $size = new ImperialUnit('df dsfsd ');
        var_dump($size);

    }
}
