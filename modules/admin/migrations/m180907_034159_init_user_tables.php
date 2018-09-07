<?php

use yii\db\Migration;

/**
 * Class m180907_034159_init_user_tables
 */
class m180907_034159_init_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // create table user
        $this->createTable('user', [
            'user_id' => $this->primaryKey(10),
            'email' => $this->string(100)->notNull()->unique(),
            'fullname' => $this->string(100)->notNull(),
            'business_unit_id' => $this->integer(10)->unsigned(),
            'notifies_unread' => $this->smallInteger(5)->defaultValue(0),
            'is_active' => $this->tinyInteger(3)->unsigned()->defaultValue(1)
        ], 'ENGINE = InnoDB DEFAULT CHARSET = utf8');
        
        // create table user_auth
        $this->createTable('user_auth', [
            'user_id' => $this->primaryKey(10),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(60)->notNull()
        ], 'ENGINE = InnoDB DEFAULT CHARSET = utf8');
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
        $this->dropTable('user_auth');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180907_034159_init_user_tables cannot be reverted.\n";

        return false;
    }
    */
}
