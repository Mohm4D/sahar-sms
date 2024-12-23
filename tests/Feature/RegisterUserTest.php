<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_call_register(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->post(route('register'), [
           'mobile' =>  '09352679303'
        ]);

        $this->assertCount(1, User::all());
    }

}
