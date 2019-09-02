<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = new User();
        $user->name = 'abc';
        $user->email = 'abc@me.com';
        $user->password = 'abcdefg123';

        $savedUser = $user->save();

        $this->assertTrue($savedUser);

        $userInfo = User::where('name', 'abc')->first();
        var_dump($userInfo);

    }
}
