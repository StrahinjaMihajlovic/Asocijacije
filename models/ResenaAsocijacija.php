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
    
    public function dodajOtvorenoPolje($poljeId){
        if(preg_match(self::vratiRegexZaAsocijaciju($poljeId),$this->otvorena_polja))
            return false;
        
        if($this->otvorena_polja === ''){
            $this->otvorena_polja .= $poljeId;
        }else{
            $this->otvorena_polja .= ',' . $poljeId;
        }
        
        return $this->save() ? $this : 0;
        
    }
    
    public function vratiOtovrenaPoljaNiz(){
        return preg_split('/[,]/', $this->otvorena_polja);
    }
    
    public function proveriPodResenjeIDodaj($idPolja, $unosKorisnika, $poljePojamAsocVeze){
        if(preg_match(self::vratiRegexZaAsocijaciju($idPolja),$this->otvorena_polja))
            return false;
       
        //$vrednost = $this->konvertujPoljeUBroj($nazivPolja, $sablonDimenzija);
        $modelVeza = $poljePojamAsocVeze[0]->vratiSpecVezu($this->asocijacija_id
                ,$idPolja
                ,Pojam::daLiPostojiSadrzajIDodaj($unosKorisnika)); //obavezna sigurnost!!
        if(isset($modelVeza) && $modelVeza->id_polja == $idPolja){
           
            foreach($modelVeza->vratiSveVezePolja($modelVeza->polje->naziv, $modelVeza->id_asocijacije) as $polje){
                if($this->dodajOtvorenoPolje($polje->polje->id) === 0){
                    return false;
                }
            }
        }
        return  $this;
        
    }
    
    private function konvertujPoljeUBroj($nazivPolja ,$duzina){ //prekopirano iz views/igra/index fajla izmenjen regex
     $polje = substr($nazivPolja, 0, 1);
     $vrednost = ((ord(strtolower($polje)) - 96) * ($duzina[0] + 1));
    
     return $vrednost;
    }
    
    public function proveriKonacnoResenje($unosKorisnika, $asocijacija,&$resenaIgra){
        $igra = $resenaIgra->igra;
        $poljeResenje = $igra->sablonIgre->resenje0; //trazimo model polja "resenje" datog sablona
        $pojamPoljeAsocVeza = PojamPoljeAsocijacija
                ::vratiSpecVezu($asocijacija->id, $poljeResenje->id);
        if($pojamPoljeAsocVeza->pojam->sadrzaj == strtolower($unosKorisnika)){ // opet sigurnost!
            foreach (PojamPoljeAsocijacija::vratiSveVezePoKriterijumu($asocijacija->id) as $poljeVeza){
                if($this->dodajOtvorenoPolje($poljeVeza->polje->id) === 0){
                    return false;
                }
            }
           $resenaIgra->resene_asocijacije = intval($resenaIgra->resene_asocijacije + 1);
           if(!$resenaIgra->save()){
               return false;
           }
        }
        
         return 1;
    }
    
    public static function vratiRegexZaAsocijaciju($poljeId){ //ovaj regex proverava da li se id polja nalazi u datom nizu
        return "/^$poljeId$|^".$poljeId.'[^\d]|[^\d]'.$poljeId.'[^\d]|[^\d]'.$poljeId.'$/m';
    }
    
    public function proveriDaLiJeResena(){
        $sablon = $this->asocijacija->vratiVezuPojamAsocijacijaPolje()->one()->polje->sablonIgre;
        $poljeResenje = $sablon->resenje0;
        if(preg_match($this->vratiRegexZaAsocijaciju($poljeResenje->id), $this->otvorena_polja)){
            return true;
        }
        return false;
    }
}
