<?php

namespace tests\unit\tools;

use app\models\Lottery;

/**
 * Class LotteryFactory
 * @package tests\unit\tools
 */
class LotteryFactory extends ModelFactory
{
    /**
     * @param array $override
     * @return Lottery
     */
    public static function create(array $override = []): Lottery
    {
        $faker = self::getFaker();

        $lottery = new Lottery();
        $lottery->title = $override['title'] ?? $faker->name;
        $lottery->money_limit = $override['money_limit'] ?? $faker->randomFloat(0, 0, 10000);
        $lottery->max_money_prize_amount = $override['max_money_prize_amount']
            ?? $faker->randomFloat(0, 0, 1000);
        $lottery->max_bonus_prize_quantity = $override['max_bonus_prize_quantity'] ?? $faker->randomNumber(2);
        $lottery->money_to_bonus_exchange_rate = $override['money_to_bonus_exchange_rate']
            ?? $faker->randomNumber(1);
        $lottery->save();

        return $lottery;
    }
}
