<?php

use yii\db\Migration;

/**
 * Class m191224_195333_pokusaji_logovanja
 */
class m191224_195333_pokusaji_logovanja extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pokusaji_logovanja', [
            'id' => 'serial',
            'korisnik_id' => 'int not null',
            'broj_pokusaja' => 'int',
            'vreme_zadnjeg' =>  'datetime default now()',
            'primary key(id)',
            'index(korisnik_id)',
            'index(vreme_zadnjeg)',
            'unique(korisnik_id)'
        ]);
        $this->addForeignKey('f_korisnik_pokusaji_logovanja', 'pokusaji_logovanja', 'korisnik_id', 'korisnik', 'id');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pokusaji_logovanja');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191224_195333_pokusaji_logovanja cannot be reverted.\n";

        return false;
    }
    */
}
