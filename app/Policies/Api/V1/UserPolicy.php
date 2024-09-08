<?php

namespace App\Policies\Api\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\Api\V1\Abilities;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user)
    {

        return $user->tokenCan(Abilities::CREATE_USER);
    }

    public function replace(User $user, User $model)
    {
        return $user->tokenCan(Abilities::REPLACE_USER);
    }

    public function update(User $user, User $model)
    {
        return $user->tokenCan(Abilities::UPDATE_USER);
    }

    public function delete(User $user, User $model)
    {
        return $user->tokenCan(Abilities::DELETE_USER);
    }
}
