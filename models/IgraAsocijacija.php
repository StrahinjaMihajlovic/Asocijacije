<?php

namespace app\models;
use yii\base\ErrorException;
use Yii;

/**
 * This is the model class for table "igra_asocijacija".
 *
 * @property string $igra_id
 * @property string $asocijacija_id
 *
 * @property Asocijacija $asocijacija
 * @property Igra $igra
 */
class IgraAsocijacija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'igra_asocijacija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['igra_id', 'asocijacija_id'], 'required'],
            [['igra_id', 'asocijacija_id'], 'integer'],
            [['igra_id', 'asocijacija_id'], 'unique', 'targetAttribute' => ['igra_id', 'asocijacija_id']],
            [['asocijacija_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asocijacija::className(), 'targetAttribute' => ['asocijacija_id' => 'id']],
            [['igra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Igra::className(), 'targetAttribute' => ['igra_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'igra_id' => 'Igra ID',
            'asocijacija_id' => 'Asocijacija ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacija()
    {
        return $this->hasOne(Asocijacija::className(), ['id' => 'asocijacija_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgra()
    {
        return $this->hasOne(Igra::className(), ['id' => 'igra_id']);
    }
    
    public function vratiAsocPovezanuSaIgrom($igraId, $nizResAsoc, $dalje = false){
        $query = IgraAsocijacija::find()->select('asocijacija_id')
                ->from('igra_asocijacija')->where('igra_id = ' . $igraId)->all(); //vracamo kao obican niz activeRecord-a
        try{
        return $query[intval($dalje ? $nizResAsoc : $nizResAsoc - 1)]; // vraca prvu nedovrsenu asocijaciju povezanu sa igrom (ako je dugme "dalje" kliknuto)
        } catch (\yii\base\ErrorException $e){
            return array_pop($query); //vraca zadnju asocijaciju ako je igra resena
        }
    }
    
    public function vratiSveAsocijacije($igraId){
        $query = self::find()->select('*')->from('igra_asocijacija')
                ->rightJoin('asocijacija',
                        'igra_asocijacija.asocijacija_id = asocijacija.id')
                ->where('igra_id = ' . $igraId);
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        
        return $provider;
    }
}
