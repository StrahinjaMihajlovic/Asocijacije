<?php

use yii\db\Migration;

/**
 * Class m200214_183832_dodajPoljeAdmin
 */
class m200214_183832_dodajPoljeAdmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('korisnik', 'je_admin','boolean default false');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('korisnik', 'je_admin');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200214_183832_dodajPoljeAdmin cannot be reverted.\n";

        return false;
    }
    */
}
