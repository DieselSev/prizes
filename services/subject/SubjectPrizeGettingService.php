<?php
namespace app\services\subject;

use app\models\SubjectPrize;
use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * Class SubjectPrizeGettingService
 * @package app\services\subject
 */
class SubjectPrizeGettingService
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
     * @return SubjectPrize
     * @throws NotFoundHttpException
     */
    public function getSubjectPrize(int $id): SubjectPrize
    {
        $prize = SubjectPrize::findOne([
            'id'      => $id,
            'user_id' => $this->user->id,
        ]);

        if (!$prize instanceof SubjectPrize) {
            throw new NotFoundHttpException('SubjectPrize has not been found');
        }

        return $prize;
    }
}