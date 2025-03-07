<?php

namespace Tests\Feature;


use App\Location;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateLocationTest extends TestCase
{
    use DatabaseTransactions;

    // As an admin, I want to be able to edit the parking locations
    public function testExample()

    {
        //create admin account
        factory(User::class)->create([
            'id' => 999,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);


        //login the admin account
        $login = $this->call('POST', 'api/auth/login',
            [
                'email' => 'Sue@gmail.com',
                'password' => 'secret',
            ]
        );
        $login->assertStatus(200);


        // insert location details into database
        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' => 0
        ]);

        factory(Location::class)->create([
            'id' => 2,
            'address' => "441 Lonsdale St, Melbourne VIC 3000",
            'latitude' => 37.806717,
            'longitude' => -144.965405,
            'slot' => 4,
            'current_car_num' => 1
        ]);


        // Update the location with correct input
        $response = $this->call('PATCH', 'api/locations/1',
            [
                'address' => 'test street',
                'latitude' => -0,
                'longitude' => 0,
                'slot' => 5,
                'current_car_num' => 5,
            ], $this->transformHeadersToServerVars([ 'Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(200);


        // Update the location with empty field
        $response = $this->call('PATCH', 'api/locations/1',
            [
                'address' => "",
                'latitude' => null,
                'longitude' => null,
                'slot' => 5,
                'current_car_num' => 0
            ], $this->transformHeadersToServerVars([ 'Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(404);
    }
}
