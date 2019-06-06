<?php
namespace app\services\subject;

use app\models\LotterySubject;
use app\models\SubjectPrize;
use Exception;
use RuntimeException;

/**
 * Class SubjectPrizeRejectionService
 * @package app\services\subject
 */
class SubjectPrizeRejectionService
{
    /**
     * @param SubjectPrize $prize
     * @return void
     */
    public function rejectPrize(SubjectPrize $prize): void
    {
        $lotterySubject = $this->getLotterySubject($prize);

        $transaction = $prize::getDb()->beginTransaction();

        try {
            $prize->is_rejected = true;
            $prize->save();

            ++$lotterySubject->quantity;
            $lotterySubject->save();

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new RuntimeException('Произошла ошибка при отказе от приза.');
        }
    }

    /**
     * @param SubjectPrize $prize
     * @return LotterySubject
     */
    private function getLotterySubject(SubjectPrize $prize): LotterySubject
    {
        $lotterySubject = LotterySubject::findOne([
            'lottery_id' => $prize->lottery_id,
            'subject_id' => $prize->subject_id,
        ]);

        if (!$lotterySubject) {
            throw new RuntimeException('Не удалось получить данные по текущему розыгрышу');
        }

        return $lotterySubject;
    }
}