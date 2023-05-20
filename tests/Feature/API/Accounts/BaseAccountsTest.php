<?php

namespace Tests\Feature\API\Accounts;

use App\Models\Accounts;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\API\BaseTest;

abstract class BaseAccountsTest extends BaseTest
{
    use RefreshDatabase;

    protected function path(): string
    {
        return "accounts";
    }

    /**
     * @param User $user
     * @param array<string, mixed> $attributes
     * @return Accounts
     */
    protected function makeItem(User $user, array $attributes = [])
    {
        $item = Accounts::factory()
            ->for($user, 'user')
            ->make($attributes);
        return $item;
    }

    /**
     * @return string[]
     */
    protected function structure(): array
    {
        return Accounts::keys();
    }

}
