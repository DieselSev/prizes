<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lotteries".
 *
 * @property int $id
 * @property string $title
 * @property float $money_limit
 * @property float $max_money_prize_amount
 * @property int $max_bonus_prize_quantity
 * @property float $money_to_bonus_exchange_rate
 * @property bool $is_active
 *
 * @property BonusPrize[] $bonusPrizes
 * @property LotterySubject[] $lotterySubjects
 * @property MoneyPrize[] $moneyPrizes
 * @property SubjectPrize[] $subjectPrizes
 */
class Lottery extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'lotteries';
    }

    public function init()
    {
        parent::init();
        $this->is_active = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'title',
                    'money_limit',
                    'max_money_prize_amount',
                    'max_bonus_prize_quantity',
                    'money_to_bonus_exchange_rate'
                ],
                'required'
            ],
            [['money_limit', 'max_money_prize_amount', 'money_to_bonus_exchange_rate'], 'number'],
            [['max_bonus_prize_quantity'], 'integer'],
            [['is_active'], 'boolean'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                           => 'ID',
            'title'                        => 'Title',
            'money_limit'                  => 'Money Limit',
            'max_money_prize_amount'       => 'Max Money Prize Amount',
            'max_bonus_prize_quantity'     => 'Max Bonus Prize Quantity',
            'money_to_bonus_exchange_rate' => 'Money To Bonus Exchange Rate',
            'is_active'                    => 'Is Active',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBonusPrizes(): ActiveQuery
    {
        return $this->hasMany(BonusPrize::class, ['lottery_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLotterySubjects(): ActiveQuery
    {
        return $this->hasMany(LotterySubject::class, ['lottery_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMoneyPrizes(): ActiveQuery
    {
        return $this->hasMany(MoneyPrize::class, ['lottery_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubjectPrizes(): ActiveQuery
    {
        return $this->hasMany(SubjectPrize::class, ['lottery_id' => 'id']);
    }
}
