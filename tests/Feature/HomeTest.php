<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function test_legacy_registro_route_redirects_to_register(): void
    {
        $response = $this->get('/registro');

        $response->assertRedirect(route('register'));
    }
}
