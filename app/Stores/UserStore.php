<?php


namespace App\Stores;


use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserStore
{
    /**
     * @param array $data
     * @return bool
     */
    public function handle(array $data): bool
    {
        $user = User::where(['id' => $data['id']])->firstOrFail();
        $email_not_unique = User::where(['email' => $data['email']])->where('id', '<>', $data['id'])->count();

        if ($email_not_unique) {
            throw new DomainException(__('user.fail.double_email'));
        }

        if ($user->role != $data['role']) {
            if (Auth::user()->role !== User::ROLE_ADMIN || Auth::user()->created_at >= $user->created_at) {
                throw new DomainException(__('user.fail.access_role'));
            }
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->setAvatar($data['avatar'] ?? null);

        if (key_exists('new_password', $data) && !empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        return $user->save();
    }
}
