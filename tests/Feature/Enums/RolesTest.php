<?php

namespace Tests\Feature\Enums;

use App\Enums\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RolesTest extends TestCase
{
    public function test_it_returns_right_roles_numbers()
    {
        $this->assertEquals(1, Roles::ADMIN->value);
        $this->assertEquals(2, Roles::USER->value);
    }
}
