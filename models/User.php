<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models
 *
 * @property integer $id
 * @property string $name
 * @property string $auth_key
 * @property string $password
 * @property string $created_at
 * @property integer $loyalty_account
 * @property string $bank_account_url
 *
 * @property BonusPrize[] $bonusPrizes
 * @property MoneyPrize[] $moneyPrizes
 * @property SubjectPrize[] $subjectPrizes
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'users';
    }

    public function init()
    {
        parent::init();
        $this->created_at = new Expression('NOW()');
        $this->loyalty_account = 0;
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'auth_key', 'password', 'created_at', 'loyalty_account'], 'required'],
            [['name', 'bank_account_url'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['auth_key'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
            [['loyalty_account'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?self
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username): ?self
    {
        return static::findOne(['name' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return ActiveQuery
     */
    public function getBonusPrizes(): ActiveQuery
    {
        return $this->hasMany(BonusPrize::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMoneyPrizes(): ActiveQuery
    {
        return $this->hasMany(MoneyPrize::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubjectPrizes(): ActiveQuery
    {
        return $this->hasMany(SubjectPrize::class, ['user_id' => 'id']);
    }
}
