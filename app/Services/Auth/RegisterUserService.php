<?php

namespace App\Services\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterUserService
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function registerReceptionist(array $data): User
    {
        return DB::transaction(fn (): User => User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => UserRole::Guest,
            'password' => $data['password'],
        ]));
    }
}
