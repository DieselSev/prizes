<?php
namespace app\services\bonus;

use app\models\BonusPrize;

/**
 * Class BonusPrizeRejectionService
 * @package app\services\bonus
 */
class BonusPrizeRejectionService
{
    /**
     * @param BonusPrize $prize
     * @return void
     */
    public function rejectPrize(BonusPrize $prize): void
    {
        $prize->is_rejected = true;
        $prize->save();
    }
}