<?php

namespace Tests\Unit;

use Tests\TestCase;

class GuestUserTest extends TestCase
{
    /** @test */
    public function it_uses_parent_users_access_token()
    {
        $this->user->update([
            'access_token' => '1234'
        ]);

        $this->assertEquals($this->user->access_token, $this->user->guestUser->access_token);
    }
}
