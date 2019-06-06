<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bonus_prizes".
 *
 * @property int $id
 * @property int $lottery_id
 * @property int $user_id
 * @property int $quantity
 * @property bool $sent_to_loyalty_account
 * @property bool $is_rejected
 *
 * @property Lottery $lottery
 * @property User $user
 */
class BonusPrize extends ActiveRecord implements PrizeInterface
{
    public const TYPE = 'bonus';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'bonus_prizes';
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
        return 'Бонусные баллы';
    }

    public function init()
    {
        parent::init();
        $this->sent_to_loyalty_account = false;
        $this->is_rejected = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['lottery_id', 'user_id', 'quantity'], 'required'],
            [['lottery_id', 'user_id', 'quantity'], 'integer'],
            [['sent_to_loyalty_account', 'is_rejected'], 'boolean'],
            [['lottery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lottery_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                      => 'ID',
            'lottery_id'              => 'Lottery ID',
            'user_id'                 => 'User ID',
            'quantity'                => 'Количество',
            'sent_to_loyalty_account' => 'Зачислен на счет лояльности',
            'is_rejected'             => 'Вы отказались от этого приза',
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
        return $this->sent_to_loyalty_account || $this->is_rejected;
    }
}
