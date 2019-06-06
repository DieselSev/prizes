<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "money_prizes".
 *
 * @property int $id
 * @property int $lottery_id
 * @property int $user_id
 * @property float $amount
 * @property bool $sent_to_bank_account
 * @property bool $converted_to_bonus_prize
 * @property bool $is_rejected
 *
 * @property Lottery $lottery
 * @property User $user
 */
class MoneyPrize extends ActiveRecord implements PrizeInterface
{
    public const TYPE = 'money';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'money_prizes';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Денежный приз';
    }

    public function init()
    {
        parent::init();
        $this->sent_to_bank_account = false;
        $this->converted_to_bonus_prize = false;
        $this->is_rejected = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['lottery_id', 'user_id', 'amount'], 'required'],
            [['lottery_id', 'user_id'], 'integer'],
            [['amount'], 'number'],
            [['sent_to_bank_account', 'converted_to_bonus_prize', 'is_rejected'], 'boolean'],
            [['lottery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lottery_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                       => 'ID',
            'lottery_id'               => 'Lottery ID',
            'user_id'                  => 'User ID',
            'amount'                   => 'Сумма',
            'sent_to_bank_account'     => 'Перечислен на банковский счет',
            'converted_to_bonus_prize' => 'Конвертирован в бонусные баллы',
            'is_rejected'              => 'Вы отказались от этого приза',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLottery(): ActiveQuery
    {
        return $this->hasOne(Lottery::class, ['id' => 'lottery_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return bool
     */
    public function isFinalState(): bool
    {
        return $this->sent_to_bank_account || $this->converted_to_bonus_prize || $this->is_rejected;
    }
}
