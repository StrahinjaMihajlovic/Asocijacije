<?php

use yii\db\Migration;

/**
 * Class m200123_225115_pojam_polje_asocijacija
 */
class m200123_225115_pojam_polje_asocijacija extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pojam_polje_asocijacija', [
            'id_asocijacije' => 'bigint unsigned',
            'id_polja' => 'bigint unsigned',
            'id_pojma' => 'bigint unsigned'
        ]);
        $this->addForeignKey('fk_veza_asocijacija', 'pojam_polje_asocijacija', 'id_asocijacije', 'asocijacija', 'id','cascade');
       $this->addForeignKey('fk_veza_polje', 'pojam_polje_asocijacija', 'id_polja', 'polje', 'id','cascade');
       $this->addForeignKey('fk_veza_pojam', 'pojam_polje_asocijacija', 'id_pojma', 'pojam', 'id','cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pojam_polje_asocijacija');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200123_225115_pojam_polje_asocijacija cannot be reverted.\n";

        return false;
    }
    */
}
