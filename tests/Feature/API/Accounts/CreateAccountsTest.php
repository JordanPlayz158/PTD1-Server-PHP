<?php

namespace Tests\Feature\API\Accounts;

class CreateAccountsTest extends BaseAccountsTest
{
    public function testCreateItemTestAccountsByGuest()
    {
        $user = $this->createUser();
        $item = $this->makeItem($user);
        $this->postJson($this->makeURI(), $item->toArray())
            ->assertStatus(404);
    }

    public function testCreateItemTestAccountsByUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->makeItem($user);
        $this->postJson($this->makeURI(), $item->toArray())
            ->assertStatus(404);
    }
}
