<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resena_igra".
 *
 * @property string $igra_id
 * @property int $korisnik_id
 * @property string $resene_asocijacije
 *
 * @property Igra $igra
 * @property Korisnik $korisnik
 */
class ResenaIgra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resena_igra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['igra_id', 'korisnik_id'], 'required'],
            [['igra_id', 'korisnik_id'], 'integer'],
            [['resene_asocijacije'], 'string', 'max' => 255],
            [['igra_id', 'korisnik_id'], 'unique', 'targetAttribute' => ['igra_id', 'korisnik_id']],
            [['igra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Igra::className(), 'targetAttribute' => ['igra_id' => 'id']],
            [['korisnik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['korisnik_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'igra_id' => 'Igra ID',
            'korisnik_id' => 'Korisnik ID',
            'resene_asocijacije' => 'Resene asocijacije'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgra()
    {
        return $this->hasOne(Igra::className(), ['id' => 'igra_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKorisnik()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'korisnik_id']);
        
    }
    
    public function kreirajVezu($korisnik, $igra){ // veza korisnik - igra, preko resena_igra
        
        $this->igra_id = $igra;
        $this->korisnik_id = $korisnik;
        /*$query = self::find()->select('count(id) as broj')->from('asocijacija')->leftJoin('igra_asocijacija', 'asocijacija_id = id')
                ->where('igra_id = '. $igra);
        str_repeat('0', $query->one()->getAttribute('broj')); 
         * ako trigger u bazi ne radi kako treba
         */
        return $this->save(true) ? $this : false;
    }
    
    public function vratiResenuIgru($idIgre, $idKorisnika){
        $query = self::find()->select('*')->from('resena_igra')
                ->where('igra_id = ' .$idIgre. ' and korisnik_id = ' . $idKorisnika);
        $provider = new \yii\data\ActiveDataProvider([
           'query' => $query 
        ]);
        return $provider->getModels()[0];
    }
    
    public function vratiBrojResenihIgara($idKorisnika){
        $modeli = $this->vratiSveReseneIgreModeli($idKorisnika);
        $igraAsocijacije = new IgraAsocijacija;
        foreach ($modeli as $kljuc => $resena_igra){
            if(intval($resena_igra->resene_asocijacije) === $igraAsocijacije->vratiSveAsocijacije($resena_igra->igra_id)->getCount()){
                $modeli[$kljuc] = new \yii\helpers\UnsetArrayValue(); // radi unset a zatim array_merge
            }
        }
        return count($modeli);
    }
    
    public function vratiSveReseneIgreModeli($idKorisnika){
         $query = self::find()->select('*')->from('resena_igra')
                ->where('korisnik_id = ' . $idKorisnika);
        $provider = new \yii\data\ActiveDataProvider([
           'query' => $query 
        ]);
        return $provider->getModels();
    }
}
