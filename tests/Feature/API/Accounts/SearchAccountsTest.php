<?php

namespace Tests\Feature\API\Accounts;

class SearchAccountsTest extends BaseAccountsTest
{
    public function testSearchItemTestAccountsByGuest()
    {
        $user = $this->createUser();
        $item = $this->makeItem($user);
        $item->save();

        $this->getJson($this->makeURI())
            ->assertStatus(404);
    }

    public function testSearchItemTestAccountsByUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->makeItem($user);
        $item->save();

        $this->getJson($this->makeURI())
            ->assertStatus(404);
    }
}
