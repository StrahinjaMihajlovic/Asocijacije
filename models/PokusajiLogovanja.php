<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pokusaji_logovanja".
 *
 * @property string $id
 * @property int $korisnik_id
 * @property int $broj_pokusaja
 * @property string $vreme_zadnjeg
 *
 * @property Korisnik $korisnik
 */
class PokusajiLogovanja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pokusaji_logovanja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['korisnik_id'], 'required'],
            [['korisnik_id', 'broj_pokusaja'], 'integer'],
            [['korisnik_id'], 'unique'],
            [['vreme_zadnjeg'], 'safe'],
            [['korisnik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['korisnik_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'korisnik_id' => 'Korisnik ID',
            'broj_pokusaja' => 'Broj Pokusaja',
            'vreme_zadnjeg' => 'Vreme Zadnjeg',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKorisnik()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'korisnik_id']);
    }
    
    public function promeniBroj(){
        $this->broj_pokusaja = intval($this->broj_pokusaja) + 1;
        if($this->broj_pokusaja >=3){
            $logger = \yii::getLogger();//upis neuspelog pokusaja u bazu
            $logger->log('korisnik sa id-om ' .$this->korisnik_id 
                    . 'se neuspesno ulogovao 3 ili vise puta'
                    , \yii\log\Logger::LEVEL_INFO, 'pokusaj_logovanja');
        }
        $this->vreme_zadnjeg = date('Y.m.d H:i:s');
        $this->save();
    }
    
    public function resetujBrojac(){
        $this->broj_pokusaja = 0;
        $this->vreme_zadnjeg = date('Y.m.d H:i:s');
        $this->save();
    }
    
    public function ubaciUBazu($korisnik){
        $this->setAttribute('korisnik_id', $korisnik);
        $this->setAttribute('broj_pokusaja', 0);
        $this->save();
    }
}
