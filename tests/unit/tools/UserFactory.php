<?php

namespace tests\unit\tools;

use app\models\User;

/**
 * Class UserFactory
 * @package tests\unit\tools
 */
class UserFactory extends ModelFactory
{
    /**
     * @param array $override
     * @return User
     */
    public static function create(array $override = []): User
    {
        $faker = self::getFaker();

        $user = new User();
        $user->name = $override['name'] ?? $faker->name;
        $user->password = $override['password'] ?? $faker->password;
        $user->auth_key = $override['auth_key'] ?? $faker->password;
        $user->bank_account_url = $override['url'] ?? $faker->url;
        $user->save();

        return $user;
    }
}
