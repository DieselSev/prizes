<?php
namespace app\services\lottery;

use app\models\Lottery;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveLotteryGettingService
 * @package app\services\lottery
 */
class ActiveLotteryGettingService
{
    /**
     * @return Lottery
     * @throws NotFoundHttpException
     */
    public function getActiveLottery(): Lottery
    {
        $lottery = Lottery::findOne(['is_active' => true]);

        if (!$lottery) {
            throw new NotFoundHttpException('Active lottery has not been found');
        }

        return $lottery;
    }
}