<?php
use yii\db\Migration;

/**
 * Class m200217_145250_init_rbac
 */
class m200217_145250_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
     public function safeUp()
    {
        $accessManager = Yii::$app->getAuthManager();
        
        $administriranje = $accessManager->createPermission('administriranje');
        $administriranje->description = 'Dozvole za korisnike tipa admin';
        $accessManager->add($administriranje);
        
        $obicniKorisnici = $accessManager->createPermission('korisnik');
        $accessManager->add($obicniKorisnici);
        
        $admin = $accessManager->createRole('admin');
        $accessManager->add($admin);
        $accessManager->addChild($admin, $administriranje);
        
        $accessManager->assign($admin, 1);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $accessManager = yii::$app->authManager;
        
        $accessManager->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200217_145250_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
