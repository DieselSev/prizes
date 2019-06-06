<?php
namespace app\services\subject;

use app\models\SubjectPrize;

/**
 * Class SubjectPrizeSendingService
 * @package app\services\subject
 */
class SubjectPrizeSendingService
{
    /**
     * @param SubjectPrize $prize
     * @return void
     */
    public function sendToPost(SubjectPrize $prize): void
    {
        $prize->sent_to_post = true;
        $prize->save();
    }
}