<?php

use yii\db\Migration;

/**
 * Class m190529_180527_create_tables
 */
class m190529_180527_create_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(100)->notNull()->unique(),
            'auth_key'         => $this->string(32)->notNull(),
            'password'         => $this->string(64)->notNull(),
            'created_at'       => $this->dateTime() . ' DEFAULT NOW()',
            'loyalty_account'  => $this->integer()->defaultValue(0),
            'bank_account_url' => $this->string(100)->notNull(),
        ]);

        $this->createTable('lotteries', [
            'id'                           => $this->primaryKey(),
            'title'                        => $this->string(100)->notNull(),
            'money_limit'                  => $this->decimal()->notNull(),
            'max_money_prize_amount'       => $this->decimal()->notNull(),
            'max_bonus_prize_quantity'     => $this->integer()->notNull(),
            'money_to_bonus_exchange_rate' => $this->decimal()->notNull(),
            'is_active'                    => $this->boolean()->defaultValue(true),
        ]);

        $this->createTable('subjects', [
            'id'    => $this->primaryKey(),
            'type'  => $this->string()->notNull()->unique(),
            'title' => $this->string(100)->notNull(),
        ]);

        $this->createTable('lottery_subjects', [
            'id'         => $this->primaryKey(),
            'lottery_id' => $this->integer()->notNull(),
            'subject_id' => $this->integer()->notNull(),
            'quantity'   => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'lottery_subjects_to_lotteries',
            'lottery_subjects',
            'lottery_id',
            'lotteries',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'lottery_subjects_to_subjects',
            'lottery_subjects',
            'subject_id',
            'subjects',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('money_prizes', [
            'id'                       => $this->primaryKey(),
            'lottery_id'               => $this->integer()->notNull(),
            'user_id'                  => $this->integer()->notNull(),
            'amount'                   => $this->float()->notNull(),
            'sent_to_bank_account'     => $this->boolean()->defaultValue(false),
            'converted_to_bonus_prize' => $this->boolean()->defaultValue(false),
            'is_rejected'              => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey(
            'money_prizes_to_lotteries',
            'money_prizes',
            'lottery_id',
            'lotteries',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'money_prizes_to_users',
            'money_prizes',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('bonus_prizes', [
            'id'                      => $this->primaryKey(),
            'lottery_id'              => $this->integer()->notNull(),
            'user_id'                 => $this->integer()->notNull(),
            'quantity'                => $this->integer()->notNull(),
            'sent_to_loyalty_account' => $this->boolean()->defaultValue(false),
            'is_rejected'             => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey(
            'bonus_prizes_to_lotteries',
            'bonus_prizes',
            'lottery_id',
            'lotteries',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'bonus_prizes_to_users',
            'bonus_prizes',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('subject_prizes', [
            'id'           => $this->primaryKey(),
            'lottery_id'   => $this->integer()->notNull(),
            'user_id'      => $this->integer()->notNull(),
            'subject_id'   => $this->integer()->notNull(),
            'sent_to_post' => $this->boolean()->defaultValue(false),
            'is_rejected'  => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey(
            'subject_prizes_to_lotteries',
            'subject_prizes',
            'lottery_id',
            'lotteries',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'subject_prizes_to_users',
            'subject_prizes',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'subject_prizes_to_subjects',
            'subject_prizes',
            'subject_id',
            'subjects',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190529_180527_create_tables cannot be reverted.\n";

        return false;
    }
}
