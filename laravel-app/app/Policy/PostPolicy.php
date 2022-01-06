<?php

namespace App\Policy;

use App\Models\User;

class PostPolicy
{
    public const ACTION_SHOW = 'show';
    public const ACTION_EDIT = 'edit';
    public const ACTION_DELETE = 'delete';

    public function show(User $user): bool
    {
        return $user->can('show posts');
    }

    public function edit(User $user): bool
    {
        return $user->can('edit posts');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete posts');
    }

}
