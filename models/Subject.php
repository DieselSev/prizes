<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "subjects".
 *
 * @property int $id
 * @property string $type
 * @property string $title
 *
 * @property LotterySubject[] $lotterySubjects
 * @property SubjectPrize[] $subjectPrizes
 */
class Subject extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'subjects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type', 'title'], 'required'],
            [['type'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 100],
            [['type'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'    => 'ID',
            'type'  => 'Type',
            'title' => 'Title',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLotterySubjects(): ActiveQuery
    {
        return $this->hasMany(LotterySubject::class, ['subject_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubjectPrizes(): ActiveQuery
    {
        return $this->hasMany(SubjectPrize::class, ['subject_id' => 'id']);
    }
}
