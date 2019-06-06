<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $prize app\models\SubjectPrize */

?>
<div class="subject-prize-view">

    <h4><?= Html::encode($prize->getTitle()) ?></h4>

    <?php if (!$prize->isFinalState()) { ?>
        <p>
            <?= Html::a(
                'Отправить по почте',
                ['subject-prizes/send-to-post', 'id' => $prize->id],
                ['class' => 'btn btn-primary']
            ) ?>
            <?= Html::a(
                'Отказаться от приза',
                ['subject-prizes/reject', 'id' => $prize->id],
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
            'sent_to_post:boolean',
            'is_rejected:boolean',
        ],
    ]) ?>

</div>
