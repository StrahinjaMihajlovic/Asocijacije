<?php

use yii\db\Migration;
use \yii\db\mysql\Schema;
/**
 * Class m190624_203619_insert_first_korisnik
 */
class m190624_203619_insert_first_korisnik extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert("korisnik", [
           'korisnicko_ime' => "admin",
            'lozinka' => Yii::$app->getSecurity()->generatePasswordHash("admin", 5),
            'email' => "admin@admin.com",
            'auth_key' => Yii::$app->getSecurity()->generateRandomString()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m190624_203619_insert_first_korisnik cannot be reverted.\n";
        $this->dropTable('korisnik');
        $this->createTable('korisnik', [
            'id'=> Schema::TYPE_PK,
            'korisnicko_ime' => $this->string(30)->notNull(),
            'email' => $this->string(30)->notNull(),
            'lozinka' => $this->string(60)->notNull(),
            'reset_kod' => $this->text(),
            'auth_key' => $this->text()
        ]);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190624_203619_insert_first_korisnik cannot be reverted.\n";

        return false;
    }
    */
}
