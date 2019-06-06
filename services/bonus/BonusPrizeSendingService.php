<?php
namespace app\services\bonus;

use app\models\BonusPrize;
use app\models\User;
use Exception;
use RuntimeException;

/**
 * Class BonusPrizeSendingService
 * @package app\services\bonus
 */
class BonusPrizeSendingService
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
     * @param BonusPrize $prize
     * @return void
     */
    public function sendToLoyaltyAccount(BonusPrize $prize): void
    {
        $transaction = $prize::getDb()->beginTransaction();

        try {
            $this->user->loyalty_account += $prize->quantity;
            $this->user->save();

            $prize->sent_to_loyalty_account = true;
            $prize->save();

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw new RuntimeException('Произошла ошибка при зачислении баллов на счет лояльности.');
        }
    }
}