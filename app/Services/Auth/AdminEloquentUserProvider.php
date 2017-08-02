<?php
namespace App\Services\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

/**
 * 自定义用户密码验证方式 取用sha1 验证
 * @auther logen
 */
class AdminEloquentUserProvider extends EloquentUserProvider
{

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     */
    public function validateCredentials(Authenticatable $user, array $credentials) {
        $plain = $credentials['password'];
        $authPassword = $user->getAuthPassword();

        return sha1($authPassword['salt'] . $plain) == $authPassword['password'];
    }
}
