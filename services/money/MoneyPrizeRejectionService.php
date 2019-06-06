<?php
namespace app\services\money;

use app\models\MoneyPrize;
use Exception;
use RuntimeException;

/**
 * Class MoneyPrizeRejectionService
 * @package app\services\money
 */
class MoneyPrizeRejectionService
{
    /**
     * @param MoneyPrize $prize
     * @return void
     */
    public function rejectPrize(MoneyPrize $prize): void
    {
        $lottery = $prize->lottery;

        $transaction = $prize::getDb()->beginTransaction();

        try {
            $prize->is_rejected = true;
            $prize->save();

            $lottery->money_limit += $prize->amount;
            $lottery->save();

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new RuntimeException('Произошла ошибка при отказе от денежного приза.');
        }
    }
}