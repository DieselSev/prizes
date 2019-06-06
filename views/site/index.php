<?php

/* @var $this yii\web\View */
/* @var $prizes app\models\PrizeInterface[] */

$this->title = $prizes ? 'Результаты розыгрыша' : 'Розыгрыш призов';

use yii\helpers\Html; ?>
<div class="site-index">

    <?php foreach ($prizes as $prize) {
        echo $this->render($prize->getType() . '-prize-view', ['prize' => $prize]);
    } ?>

    <?php
    if (!$prizes) {
        echo Html::beginForm(['/'])
            . Html::submitButton('Разыграть приз', ['class' => 'btn btn-primary'])
            . Html::endForm();
    }
    ?>
</div>
