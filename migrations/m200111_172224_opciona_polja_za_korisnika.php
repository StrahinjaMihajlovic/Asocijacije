<?php

use yii\db\Migration;

/**
 * Class m200111_172224_opciona_polja_za_korisnika
 */
class m200111_172224_opciona_polja_za_korisnika extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('korisnik', 'prebivaliste', 'varchar(20)');
        $this->addColumn('korisnik', 'pol', 'boolean');
        $this->addColumn('korisnik', 'datum_rodjenja', 'date');
        $this->addColumn('korisnik', 'zanimanje', 'varchar(20)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('alter table korisnik '
                . 'drop column prebivaliste,'
                . 'drop column pol,'
                . 'drop column datum_rodjenja,'
                . 'drop column zanimanje');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200111_172224_opciona_polja_za_korisnika cannot be reverted.\n";

        return false;
    }
    */
}
