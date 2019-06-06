<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $prize app\models\MoneyPrize */

?>
<div class="money-prize-view">

    <h4><?= Html::encode($prize->getTitle()) ?></h4>

    <?php if (!$prize->isFinalState()) { ?>
    <p>
        <?= Html::a(
            'Отправить на счет',
            ['money-prizes/send-to-bank-account', 'id' => $prize->id],
            ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a(
            'Конветрировать в бонусные баллы',
            ['money-prizes/convert-to-bonus-prize', 'id' => $prize->id],
            ['class' => 'btn btn-info']
        ) ?>
        <?= Html::a(
            'Отказаться от приза',
            ['money-prizes/reject', 'id' => $prize->id],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите отказаться от приза?',
                ],
            ]
        ) ?>
    </p>
    <?php } ?>

    <?= DetailView::widget([
        'model' => $prize,
        'attributes' => [
            'amount',
            'sent_to_bank_account:boolean',
            'converted_to_bonus_prize:boolean',
            'is_rejected:boolean',
        ],
    ]) ?>

</div>
