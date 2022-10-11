<?php

namespace Modules\Acl\Services;

use Modules\User\Models\User;
use Illuminate\Support\Collection;

class GetUserPermissions
{
    public function run(int $userId): array
    {
        $user = User::with(['permissions' => function ($query) {
            $query->get(['id', 'name']);
        }])->findOrFail($userId);

        // ray($user->permissions);

        //if has direct permissions use it
        if ($user->permissions->count()) {
            return $this->mapPermissions($user->permissions);
            //get the permissions via roles
        } else {
            return $this->mapPermissions($user->getAllPermissions());
        }
    }

    private function mapPermissions(Collection $permissions): array
    {
        return $permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name
            ];
        })->toArray();
    }
}
