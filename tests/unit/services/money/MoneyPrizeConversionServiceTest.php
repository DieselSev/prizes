<?php

namespace tests\unit\services\money;

use app\models\Lottery;
use app\models\User;
use app\services\money\MoneyPrizeConversionService;
use Codeception\Test\Unit;
use tests\unit\tools\LotteryFactory;
use tests\unit\tools\MoneyPrizeFactory;
use tests\unit\tools\UserFactory;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

/**
 * Class MoneyPrizeConversionServiceTest
 * @package tests\unit\services\money
 */
class MoneyPrizeConversionServiceTest extends Unit
{
    protected function _before()
    {
        User::deleteAll();
        Lottery::deleteAll();
    }

    /**
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function testConversion(): void
    {
        $user = UserFactory::create();
        $lottery = LotteryFactory::create();
        $moneyPrize = MoneyPrizeFactory::create([
            'user_id' => $user->id,
            'lottery_id' => $lottery->id,
        ]);

        /** @var MoneyPrizeConversionService $service */
        $service = Yii::$container->get(MoneyPrizeConversionService::class);

        $bonusPrize = $service->convertToBonusPrize($moneyPrize);

        $this->assertTrue($moneyPrize->converted_to_bonus_prize);

        $this->assertEquals($user->id, $bonusPrize->user_id);
        $this->assertEquals($lottery->id, $bonusPrize->lottery_id);
        $quantity = ceil($moneyPrize->amount / $lottery->money_to_bonus_exchange_rate);

        $this->assertEquals($quantity, $bonusPrize->quantity);
    }
}
