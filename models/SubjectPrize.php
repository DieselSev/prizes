<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "subject_prizes".
 *
 * @property int $id
 * @property int $lottery_id
 * @property int $user_id
 * @property int $subject_id
 * @property bool $sent_to_post
 * @property bool $is_rejected
 *
 * @property Lottery $lottery
 * @property Subject $subject
 * @property User $user
 */
class SubjectPrize extends ActiveRecord implements PrizeInterface
{
    public const TYPE = 'subject';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'subject_prizes';
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
        return $this->subject->title;
    }

    public function init()
    {
        parent::init();
        $this->sent_to_post = false;
        $this->is_rejected = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['lottery_id', 'user_id', 'subject_id'], 'required'],
            [['lottery_id', 'user_id', 'subject_id'], 'integer'],
            [['sent_to_post', 'is_rejected'], 'boolean'],
            [['lottery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lottery_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subject_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => 'ID',
            'lottery_id'   => 'Lottery ID',
            'user_id'      => 'User ID',
            'subject_id'   => 'Subject ID',
            'sent_to_post' => 'Отправлен по почте',
            'is_rejected'  => 'Вы отказались от этого приза',
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
        return $this->sent_to_post || $this->is_rejected;
    }
}
