<?php
namespace app\services\bonus;

use app\models\BonusPrize;
use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * Class BonusPrizeGettingService
 * @package app\services\bonus
 */
class BonusPrizeGettingService
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
     * @return BonusPrize
     * @throws NotFoundHttpException
     */
    public function getBonusPrize(int $id): BonusPrize
    {
        $prize = BonusPrize::findOne([
            'id'      => $id,
            'user_id' => $this->user->id,
        ]);

        if (!$prize instanceof BonusPrize) {
            throw new NotFoundHttpException('BonusPrize has not been found');
        }

        return $prize;
    }
}