<?php

namespace tests\unit\tools;

use app\models\MoneyPrize;

/**
 * Class MoneyPrizeFactory
 * @package tests\unit\tools
 */
class MoneyPrizeFactory extends ModelFactory
{
    /**
     * @param array $override
     * @return MoneyPrize
     */
    public static function create(array $override = []): MoneyPrize
    {
        $faker = self::getFaker();

        $moneyPrize = new MoneyPrize();
        $moneyPrize->lottery_id = $override['lottery_id'] ?? $faker->randomNumber(1);
        $moneyPrize->user_id = $override['user_id'] ?? $faker->randomNumber(1);
        $moneyPrize->amount = $override['amount'] ?? $faker->randomFloat(2, 0,1000);
        $moneyPrize->save();

        return $moneyPrize;
    }
}
