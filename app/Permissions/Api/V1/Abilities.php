<?php

namespace App\Permissions\Api\V1;

use App\Models\User;

final class Abilities
{
    public const CREATE_TICKET = 'ticket:create';
    public const UPDATE_TICKET = 'ticket:update';
    public const REPLACE_TICKET = 'ticket:replace';
    public const DELETE_TICKET = 'ticket:delete';

    public const CREATE_OWN_TICKET = 'ticket:own:create';
    public const UPDATE_OWN_TICKET = 'ticket:own:update';
    public const REPLACE_OWN_TICKET = 'ticket:own:replace';
    public const DELETE_OWN_TICKET = 'ticket:own:delete';

    public const CREATE_USER = 'user:create';
    public const UPDATE_USER = 'user:update';
    public const REPLACE_USER = 'user:replace';
    public const DELETE_USER = 'user:delete';

    public static function getAbilities(User $user)
    {
        if ($user->is_manager) {
            return [
                self::CREATE_TICKET,
                self::UPDATE_TICKET,
                self::REPLACE_TICKET,
                self::DELETE_TICKET,
                self::CREATE_USER,
                self::UPDATE_USER,
                self::REPLACE_USER,
                self::DELETE_USER,
            ];
        } else {
            return [
                self::CREATE_OWN_TICKET,
                self::UPDATE_OWN_TICKET,
                self::DELETE_OWN_TICKET,
            ];
        }
    }
}
