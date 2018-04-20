<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function user_can_have_parent_user()
    {
        $parentUser = factory(User::class)->create();
        $user = factory(User::class)->create([
            'parent_id' =>  $parentUser->getKey()
        ]);

        $this->assertTrue($user->hasParent());
    }
}
