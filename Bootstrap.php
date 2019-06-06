<?php

namespace app;

use app\models\User;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package app
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritDoc
     */
    public function bootstrap($app): void
    {
        Yii::$container->set(User::class, function () {
            return Yii::$app->user->identity ?? new User();
        });
    }
}