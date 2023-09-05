<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('userStatus', function (User $user) {
    $user->role = \MaaximOne\LaAdmin\Models\Role::findOrFail($user->role_id);
    if ($user->role_id > 1) return $user;
    return false;
});

Broadcast::channel('MaaximOne.LaAdmin.Models.Role.{id}', function ($user, $id) {
    return (int)$user->role_id === (int)$id;
});
