<?php

use yii\db\Migration;

/**
 * Class m191231_210247_reportPolja
 */
class m191231_210247_reportPolja extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('korisnik', 'datum_registrovanja', 'datetime default now()');
        $this->addColumn('asocijacija', 'datum_kreiranja', 'datetime default now()');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('korisnik', 'datum_registrovanja');
        $this->dropColumn('asocijacija', 'datum_kreiranja');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191231_210247_reportPolja cannot be reverted.\n";

        return false;
    }
    */
}
