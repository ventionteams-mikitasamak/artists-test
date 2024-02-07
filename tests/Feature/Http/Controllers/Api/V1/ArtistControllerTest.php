<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ArtistControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('responsecache:clear');
    }

    public function test_endpoint_returns_valid_data(): void
    {
        $createdArtists = Artist::factory(64)->create();

        $response = $this->json('get', '/api/v1/artists')->assertOk();

        $responseArtists = collect($response->json('data'));
        $createdArtists->each(function ($artist) use ($responseArtists) {
            $this->assertTrue($responseArtists->contains('id', $artist->id));
        });
    }

    public function test_endpoint_returns_cached_data(): void
    {
        Artist::factory(32)->create(['active' => 1]);

        $this->json('get', '/api/v1/artists')->assertOk();

        Artist::factory(16)->create(['active' => 1]);

        $response = $this->json('get', '/api/v1/artists')->assertOk();
        $this->assertEquals(32, collect($response->json('data'))->count());

        $response = $this->json('get', '/api/v1/artists?filter[active]=1')->assertOk();
        $this->assertEquals(48, collect($response->json('data'))->count());

        Artist::factory(16)->create(['active' => 1]);

        $response = $this->json('get', '/api/v1/artists?filter[active]=1')->assertOk();
        $this->assertEquals(48, collect($response->json('data'))->count());
    }

    public function test_endpoint_returns_paginated_data(): void
    {
        Artist::factory(128)->create();

        $response = $this->json('get', '/api/v1/artists')->assertOk();

        $responseArtists = collect($response->json('data'));

        $this->assertEquals(100, $responseArtists->count());
        $this->assertTrue(collect($response->json())->has('links'));
    }

    public function test_endpoint_filtered_by_activity_returns_relevant_artists_only(): void
    {
        Artist::factory(128)->create();

        $response = $this->json('get', '/api/v1/artists?filter[active]=1')->assertOk();

        $responseArtists = collect($response->json('data'));
        $responseArtists->each(function ($artist) {
            $this->assertEquals(1, $artist['active']);
        });

        $response = $this->json('get', '/api/v1/artists?filter[active]=0')->assertOk();

        $responseArtists = collect($response->json('data'));
        $responseArtists->each(function ($artist) {
            $this->assertEquals(0, $artist['active']);
        });
    }

    public function test_endpoint_filtered_by_email_returns_relevant_artists_only(): void
    {
        Artist::factory(128)->create();

        collect(['@yahoo.com', '@gmail.com', '@hotmail.com'])->each(function ($filter) {
            $response = $this->json('get', "/api/v1/artists?filter[email]={$filter}")->assertOk();

            $responseArtists = collect($response->json('data'));
            $responseArtists->each(function ($artist) use ($filter) {
                $this->assertTrue(str_contains($artist['email'], $filter));
            });
        });

        $response = $this->json('get', '/api/v1/artists?filter[email]=impossible@@@filter')->assertOk();
        $this->assertEquals(0, collect($response->json('data'))->count());
    }

    public function test_endpoint_returns_unprocessable_when_wrong_data_type_is_used_in_activity_filter(): void
    {
        $this->json('get', '/api/v1/artists?filter[active]=wrong_data_type')->assertUnprocessable();
    }
}
