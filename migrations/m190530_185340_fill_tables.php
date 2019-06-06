<?php

use app\models\Lottery;
use app\models\LotterySubject;
use app\models\Subject;
use app\models\User;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m190530_185340_fill_tables
 */
class m190530_185340_fill_tables extends Migration
{
    /**
     * Так как регистрация пользователей и разработка админки не требуется, заполняем базу демонстрационными данными
     *
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $url = 'https://yandex.ru/';
        $security = Yii::$app->security;
        $this->batchInsert(User::tableName(),
            ['name', 'auth_key', 'password', 'bank_account_url'],
            [
                ['Иван', $security->generateRandomString(), $security->generatePasswordHash('1234'), $url],
                ['Павел', $security->generateRandomString(), $security->generatePasswordHash('4321'), $url],
                ['Кирилл', $security->generateRandomString(), $security->generatePasswordHash('1111'), $url],
            ]
        );

        $this->insert(Lottery::tableName(), [
            'title'                        => 'Первый розыгрыш',
            'money_limit'                  => 10000,
            'max_money_prize_amount'       => 1000,
            'max_bonus_prize_quantity'     => 100,
            'money_to_bonus_exchange_rate' => 10,
        ]);

        $this->batchInsert(Subject::tableName(),
            ['type', 'title'],
            [
                ['phone', 'Телефон'],
                ['tablet', 'Планшет'],
                ['laptop', 'Ноутбук'],
            ]
        );

        $lotteryId = (new Query())
            ->select('id')
            ->from(Lottery::tableName())
            ->scalar();

        $subjectIds = (new Query())
            ->select('id')
            ->from(Subject::tableName())
            ->column();

        foreach ($subjectIds as $subjectId) {
            $this->insert(LotterySubject::tableName(), [
                'lottery_id' => $lotteryId,
                'subject_id' => $subjectId,
                'quantity'   => 3
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190530_185340_fill_tables cannot be reverted.\n";

        return false;
    }
}
