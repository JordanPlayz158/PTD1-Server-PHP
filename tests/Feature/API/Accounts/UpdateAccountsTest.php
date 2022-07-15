<?php

namespace Tests\Feature\API\Accounts;

class UpdateAccountsTest extends BaseAccountsTest
{
    public function testUpdateItemTestAccountsByGuest()
    {
        $user = $this->createUser();
        $item = $this->makeItem($user);
        $item->save();

        $this->patchJson($this->makeURI($item->id), $item->toArray())
            ->assertStatus(404);
    }

    public function testUpdateItemTestAccountsByUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->makeItem($user);
        $item->save();

        $this->patchJson($this->makeURI($item->id), $item->toArray())
            ->assertStatus(404);
    }
}
