<?php

namespace App\Policies\Api\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\Api\V1\Abilities;

class TicketPolicy
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

        return $user->tokenCan(Abilities::CREATE_TICKET) || $user->tokenCan(Abilities::CREATE_OWN_TICKET);
    }

    public function replace(User $user, Ticket $ticket)
    {
        return $user->tokenCan(Abilities::REPLACE_TICKET);
    }

    public function update(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::UPDATE_TICKET)) {
            return true;
        } else if ($user->tokenCan(Abilities::UPDATE_OWN_TICKET)) {
            return $user->id === $ticket->user_id;
        }
        return false;
    }

    public function delete(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::DELETE_TICKET)) {
            return true;
        } else if ($user->tokenCan(Abilities::DELETE_OWN_TICKET)) {
            return $user->id === $ticket->user_id;
        }
        return false;
    }
}
