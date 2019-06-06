<?php
namespace app\services\money;

use app\models\MoneyPrize;
use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * Class MoneyPrizeGettingService
 * @package app\services\money
 */
class MoneyPrizeGettingService
{
    /**
     * @var User
     */
    private $user;

    /**
     * BonusPrizeSendingService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $id
     * @return MoneyPrize
     * @throws NotFoundHttpException
     */
    public function getMoneyPrize(int $id): MoneyPrize
    {
        $prize = MoneyPrize::findOne([
            'id'      => $id,
            'user_id' => $this->user->id,
        ]);

        if (!$prize instanceof MoneyPrize) {
            throw new NotFoundHttpException('MoneyPrize has not been found');
        }

        return $prize;
    }
}