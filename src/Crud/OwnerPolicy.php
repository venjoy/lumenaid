<?php

namespace Venjoy\Lumenaid\Crud;

class OwnerPolicy
{
    public function isLoggedIn($user)
    {
        // As long as the is real, allowed

        return $user->id != null;
    }

    public function isSelf($user, $item)
    {
        // Only if the is the owner of the item

        return $user->id == $item['user_id'];
    }
}