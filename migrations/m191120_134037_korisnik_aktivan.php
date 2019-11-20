<?php

use yii\db\Migration;

/**
 * Class m191120_134037_korisnik_aktivan
 */
class m191120_134037_korisnik_aktivan extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('korisnik', 'aktivan', 'bool');
        $this->update('korisnik', ['aktivan' => 1]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('korisnik', 'aktivan');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191120_134037_korisnik_aktivan cannot be reverted.\n";

        return false;
    }
    */
}
