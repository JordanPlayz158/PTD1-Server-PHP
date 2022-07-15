<?php

namespace Tests\Feature\API\Accounts;

class DeleteAccountsTest extends BaseAccountsTest
{
    public function testDeleteItemTestAccountsByGuest()
    {
        $user = $this->createUser();
        $item = $this->makeItem($user);
        $item->save();

        $this->deleteJson($this->makeURI($item->id))
            ->assertStatus(404);
    }

    public function testDeleteItemTestAccountsByUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->makeItem($user);
        $item->save();

        $this->deleteJson($this->makeURI($item->id))
            ->assertStatus(404);
    }
}
