<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadUnitsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_request_a_list_of_units()
    {
        $this->get('/units')->assertSuccessful();

        $unit = factory('App\Unit')->create();

        $this->get('/units')->assertJsonFragment(['name' => $unit->name]);
    }

    /** @test */
    public function a_user_can_request_a_single_unit()
    {
        $unit = factory('App\Unit')->create();

        $this->get("/units/{$unit->id}")->assertJsonFragment(['name' => $unit->name]);
    }

    /** @test */
    public function a_user_can_search_by_type()
    {
        $searchedUnit = factory('App\Unit')->create([
            'type' => 'Long Term Rental'
        ]);

        $notSearchedUnit = factory('App\Unit')->create([
            'type' => 'Vacation Rental'
        ]);

        $this->get("/long-term-rentals")
             ->assertJsonFragment(['name' => $searchedUnit->name])
             ->assertJsonMissing(['name' => $notSearchedUnit->name]);
    }

    /** @test */
    public function a_user_can_search_by_beachfront()
    {
        $notSearchedUnit = factory('App\Unit')->create([
            'location' => 'Between Hwy-Beach'
        ]);

        $searchedUnit = factory('App\Unit')->create([
            'type' => 'Beachfront'
        ]);

        $this->get("/beachfront")
             ->assertJsonFragment(['name' => $searchedUnit->name])
             ->assertJsonMissing(['name' => $notSearchedUnit->name]);
    }
}
