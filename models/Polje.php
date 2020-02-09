<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "polje".
 *
 * @property string $id
 * @property string $sablon_igre_id
 * @property string $naziv
 *
 * @property SablonIgre $sablonIgre
 * @property SablonIgre $sablonIgre0
 */
class Polje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'polje';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sablon_igre_id'], 'integer'],
            [['naziv'], 'required'],
            [['naziv'], 'string'],
            [['sablon_igre_id'], 'exist', 'skipOnError' => true, 'targetClass' => SablonIgre::className(), 'targetAttribute' => ['sablon_igre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sablon_igre_id' => 'Sablon Igre ID',
            'naziv' => 'Naziv',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSablonIgre()
    {
        return $this->hasOne(SablonIgre::className(), ['id' => 'sablon_igre_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSablonIgre0()
    {
        return $this->hasOne(SablonIgre::className(), ['resenje' => 'id']);
    }
    
    public function getPojamPoljeAsocijacije(){
        return $this->hasMany(PojamPoljeAsocijacija::class, ['id_polja' => 'id']);
    }
    
    public function vratiSvaPoljaSablona($idSablona){
        $query = self::find()->select('id, naziv')->from('polje')
                ->where('sablon_igre_id = '.$idSablona);
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        return $provider->getModels();
    }
}
