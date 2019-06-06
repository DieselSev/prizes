<?php
namespace app\services\lottery;

use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * Class UserPrizesGettingService
 * @package app\services\lottery
 */
class UserPrizesGettingService
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var ActiveLotteryGettingService
     */
    private $activeLotteryGettingService;

    /**
     * UserPrizesGettingService constructor.
     * @param User $user
     * @param ActiveLotteryGettingService $activeLotteryGettingService
     */
    public function __construct(User $user, ActiveLotteryGettingService $activeLotteryGettingService)
    {
        $this->user = $user;
        $this->activeLotteryGettingService = $activeLotteryGettingService;
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function getCurrentUserPrizesForActiveLottery(): array
    {
        $activeLottery = $this->activeLotteryGettingService->getActiveLottery();
        $condition = ['lottery_id' => $activeLottery->id];

        return array_merge(
            $this->user->getMoneyPrizes()->where($condition)->all(),
            $this->user->getBonusPrizes()->where($condition)->all(),
            $this->user->getSubjectPrizes()->where($condition)->all()
        );
    }
}