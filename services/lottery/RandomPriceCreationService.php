<?php
namespace app\services\lottery;

use app\models\BonusPrize;
use app\models\Lottery;
use app\models\LotterySubject;
use app\models\MoneyPrize;
use app\models\PrizeInterface;
use app\models\SubjectPrize;
use app\models\User;
use Exception;
use RuntimeException;

/**
 * Class RandomPriceCreationService
 * @package app\services\lottery
 */
class RandomPriceCreationService
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
     * RandomPriceCreationService constructor.
     * @param User $user
     * @param ActiveLotteryGettingService $activeLotteryGettingService
     */
    public function __construct(User $user, ActiveLotteryGettingService $activeLotteryGettingService)
    {
        $this->user = $user;
        $this->activeLotteryGettingService = $activeLotteryGettingService;
    }

    /**
     * @return PrizeInterface
     * @throws Exception
     */
    public function createPrize(): PrizeInterface
    {
        $transaction = $this->user::getDb()->beginTransaction();

        try {
            $activeLottery = $this->activeLotteryGettingService->getActiveLottery();

            $availablePrizeTypes = [BonusPrize::TYPE];

            if ($activeLottery->money_limit) {
                $availablePrizeTypes[] = MoneyPrize::TYPE;
            }

            $availableLotterySubjects = $this->getAvailableLotterySubjects($activeLottery);
            if ($availableLotterySubjects) {
                $availablePrizeTypes[] = SubjectPrize::TYPE;
            }

            $typeIndex = random_int(0, count($availablePrizeTypes) -1);
            switch ($availablePrizeTypes[$typeIndex]) {
                case MoneyPrize::TYPE:
                    $prize = $this->createMoneyPrize($activeLottery);
                    break;
                case SubjectPrize::TYPE:
                    $prize = $this->createSubjectPrize($activeLottery, ...$availableLotterySubjects);
                    break;
                default:
                    $prize = $this->createBonusPrize($activeLottery);
            }

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new RuntimeException('Произошла ошибка при розыгрыше приза. Повторите попытку позднее.');
        }

        return $prize;
    }

    /**
     * @param Lottery $activeLottery
     * @return MoneyPrize
     * @throws Exception
     */
    private function createMoneyPrize(Lottery $activeLottery): MoneyPrize
    {
        $maxAmount = $activeLottery->max_money_prize_amount;
        if ($maxAmount > $activeLottery->money_limit) {
            $maxAmount = $activeLottery->money_limit;
        }
        $amount = random_int(0, $maxAmount * 100) / 100;

        $prize = new MoneyPrize();
        $prize->lottery_id = $activeLottery->id;
        $prize->user_id = $this->user->id;
        $prize->amount = $amount;
        $prize->save();

        $activeLottery->money_limit -= $amount;
        $activeLottery->save();

        return $prize;
    }

    /**
     * @param Lottery $activeLottery
     * @return BonusPrize
     * @throws Exception
     */
    private function createBonusPrize(Lottery $activeLottery): BonusPrize
    {
        $prize = new BonusPrize();
        $prize->lottery_id = $activeLottery->id;
        $prize->user_id = $this->user->id;
        $prize->quantity = random_int(0, $activeLottery->max_bonus_prize_quantity);
        $prize->save();

        return $prize;
    }

    /**
     * @param Lottery $activeLottery
     * @param LotterySubject[] $availableLotterySubjects
     * @return SubjectPrize
     * @throws Exception
     */
    private function createSubjectPrize(
        Lottery $activeLottery,
        LotterySubject ...$availableLotterySubjects
    ): SubjectPrize {
        $subject = $availableLotterySubjects[random_int(0, count($availableLotterySubjects) - 1)];

        $prize = new SubjectPrize();
        $prize->lottery_id = $activeLottery->id;
        $prize->user_id = $this->user->id;
        $prize->subject_id = $subject->subject_id;
        $prize->save();

        --$subject->quantity;
        $subject->save();

        return $prize;
    }

    /**
     * @param Lottery $lottery
     * @return array
     */
    private function getAvailableLotterySubjects(Lottery $lottery): array
    {
        $availableLotterySubjects = [];
        foreach ($lottery->lotterySubjects as $lotterySubject) {
            if ($lotterySubject->quantity) {
                $availableLotterySubjects[] = $lotterySubject;
            }
        }

        return $availableLotterySubjects;
    }
}