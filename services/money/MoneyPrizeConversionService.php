<?php
namespace app\services\money;

use app\models\BonusPrize;
use app\models\MoneyPrize;
use Exception;
use RuntimeException;

/**
 * Class MoneyPrizeConversionService
 * @package app\services\money
 */
class MoneyPrizeConversionService
{
    /**
     * @param MoneyPrize $moneyPrize
     * @return BonusPrize
     */
    public function convertToBonusPrize(MoneyPrize $moneyPrize): BonusPrize
    {
        $transaction = $moneyPrize::getDb()->beginTransaction();

        try {
            $lottery = $moneyPrize->lottery;

            $bonusPrize = new BonusPrize();
            $bonusPrize->lottery_id = $moneyPrize->lottery_id;
            $bonusPrize->user_id = $moneyPrize->user_id;
            $bonusPrize->quantity = ceil($moneyPrize->amount / $lottery->money_to_bonus_exchange_rate);
            $bonusPrize->save();

            $moneyPrize->converted_to_bonus_prize = true;
            $moneyPrize->save();

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new RuntimeException('Произошла ошибка при конвертации денежного приза.');
        }

        return $bonusPrize;
    }
}