<?php

use yii\db\Migration;

/**
 * Class m200212_201011_ukloni_pojmove_ids
 */
class m200212_201011_ukloni_pojmove_ids extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('asocijacija','pojmovi_ids');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('asocijacija', 'pojmovi_ids', 'Varchar(255)');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200212_201011_ukloni_pojmove_ids cannot be reverted.\n";

        return false;
    }
    */
}
