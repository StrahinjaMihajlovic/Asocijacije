<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sablon_igre".
 *
 * @property string $id
 * @property string $resenje
 * @property string $naziv
 *
 * @property Igra[] $igras
 * @property Polje[] $poljes
 * @property Polje $resenje0
 */
class SablonIgre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sablon_igre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resenje', 'naziv'], 'required'],
            [['resenje'], 'integer'],
            [['naziv'], 'string', 'max' => 10],
            [['resenje'], 'unique'],
            [['naziv'], 'unique'],
            [['resenje'], 'exist', 'skipOnError' => true, 'targetClass' => Polje::className(), 'targetAttribute' => ['resenje' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resenje' => 'Resenje',
            'naziv' => 'Naziv',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgras()
    {
        return $this->hasMany(Igra::className(), ['sablon_igre_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoljes()
    {
        return $this->hasMany(Polje::className(), ['sablon_igre_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResenje0()
    {
        return $this->hasOne(Polje::className(), ['id' => 'resenje']);
    }
    
    public function vratiSablon($idSablona){
        $query = self::find()->select('id, naziv')->from('sablon_igre')
                ->where('id = ' . $idSablona);
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        
        return $provider->getModels()[0];
    }
    
    public function vratiSablonKaoNiz(){
        return preg_split('/[x]/', $this->naziv);
        
    }
    
    public function vratiSvaPoljaPoRedu(){
        $poljaQuery = $this->poljes;
        /*$poljaNizModel = $poljaQuery->orderBy('naziv')->all();
        $poljaNiz =$poljaQuery->asArray(true)->orderBy('naziv')->all();
        $kljucResenja = array_search('resenje', array_column($poljaNiz, 'naziv'));
        $poljeResenja = $poljaNizModel[$kljucResenja];
        $poljaNizModel[$kljucResenja] = $poljaNizModel[0];
        $poljaNizModel[0] = $poljeResenja;
        return $poljaNizModel;*/
        return $poljaQuery;
    }
}
