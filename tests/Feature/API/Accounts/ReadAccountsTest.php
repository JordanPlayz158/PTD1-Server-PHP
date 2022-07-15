<?php

namespace Tests\Feature\API\Accounts;

class ReadAccountsTest extends BaseAccountsTest
{
    public function testReadItemTestAccountsByGuest()
    {
        $user = $this->createUser();
        $item = $this->makeItem($user);
        $item->save();

        $this->getJson($this->makeURI($item->id))
            ->assertStatus(404);
    }

    public function testReadItemTestAccountsByUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->makeItem($user);
        $item->save();

        $this->getJson($this->makeURI($item->id))
            ->assertStatus(404);
    }
}
