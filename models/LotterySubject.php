<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lottery_subjects".
 *
 * @property int $id
 * @property int $lottery_id
 * @property int $subject_id
 * @property int $quantity
 *
 * @property Lottery $lottery
 * @property Subject $subject
 */
class LotterySubject extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'lottery_subjects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['lottery_id', 'subject_id', 'quantity'], 'required'],
            [['lottery_id', 'subject_id', 'quantity'], 'integer'],
            [['lottery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lottery_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'lottery_id' => 'Lottery ID',
            'subject_id' => 'Subject ID',
            'quantity'   => 'Quantity',
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
    public function getSubject(): ActiveQuery
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }
}
