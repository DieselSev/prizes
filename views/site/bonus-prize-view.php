<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $prize app\models\BonusPrize */

?>
<div class="bonus-prize-view">

    <h4><?= Html::encode($prize->getTitle()) ?></h4>

    <?php if (!$prize->isFinalState()) { ?>
        <p>
            <?= Html::a(
                'Зачислить на счет лояльности',
                ['bonus-prizes/send-to-loyalty-account', 'id' => $prize->id],
                ['class' => 'btn btn-primary']
            ) ?>
            <?= Html::a(
                'Отказаться от приза',
                ['bonus-prizes/reject', 'id' => $prize->id],
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
            'quantity',
            'sent_to_loyalty_account:boolean',
            'is_rejected:boolean',
        ],
    ]) ?>

</div>
