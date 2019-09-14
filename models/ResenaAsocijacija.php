<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resena_asocijacija".
 *
 * @property string $asocijacija_id
 * @property int $korisnik_id
 * @property string $otvorena_polja
 *
 * @property Asocijacija $asocijacija
 * @property Korisnik $korisnik
 */
class ResenaAsocijacija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resena_asocijacija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asocijacija_id', 'korisnik_id'], 'required'],
            [['asocijacija_id', 'korisnik_id'], 'integer'],
            [['otvorena_polja'], 'string', 'max' => 255],
            [['asocijacija_id', 'korisnik_id'], 'unique', 'targetAttribute' => ['asocijacija_id', 'korisnik_id']],
            [['asocijacija_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asocijacija::className(), 'targetAttribute' => ['asocijacija_id' => 'id']],
            [['korisnik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['korisnik_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asocijacija_id' => 'Asocijacija ID',
            'korisnik_id' => 'Korisnik ID',
            'otvorena_polja' => 'Otvorena Polja',
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
    public function getKorisnik()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'korisnik_id']);
    }
    
    public function kreirajVezu($IdAsoc, $IdKorisnik){
        $this->asocijacija_id = $IdAsoc;
        $this->korisnik_id = $IdKorisnik;
       $this->otvorena_polja = '';
        return  $this->save() ? $this : false;
    }
    
    public function proveriVezu($IdAsoc, $IdKorisnik ){
        $query = ResenaAsocijacija::find()->select('*')
                ->from('resena_asocijacija')->where('asocijacija_id = '
                        . $IdAsoc . ' AND korisnik_id = ' . $IdKorisnik);
        $provider = new \yii\data\ActiveDataProvider([
                'query' =>$query
                ]);
        return empty($provider->getModels()) ? $this->kreirajVezu($IdAsoc, $IdKorisnik) 
                : $provider->getModels()[0];
        
    }
    
    public function dodajOtvorenoPolje($poljeNaziv){
        if($this->otvorena_polja === ''){
            $this->otvorena_polja .= $poljeNaziv;
        }else{
            $this->otvorena_polja .= ', ' . $poljeNaziv;
        }
        
        return $this->save() ? $this : false;
        
    }
    
    public function vratiOtovrenaPoljaNiz(){
        return preg_split('/[,]/', $this->otvorena_polja);
    }
}
