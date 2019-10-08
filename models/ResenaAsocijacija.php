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
        if(stripos($this->otvorena_polja, $poljeNaziv) !== false)
                return false;
        
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
    
    public function proveriPodResenjeIDodaj($nazivPolja, $unosKorisnika, $sablonDimenzija, $nosilac){
        if(preg_match('/' .$nazivPolja . ',|'.$nazivPolja.'$/',$this->otvorena_polja))
            return false;
        
        $vrednost = $this->konvertujPoljeUBroj($nazivPolja, $sablonDimenzija);
        
        foreach($nosilac->nizPojmova as $pojam){
            
            if ($pojam['id'] == $nosilac->RedosledPojmova[$vrednost] 
                    &&  (strcasecmp($pojam['sadrzaj'], $unosKorisnika)) === 0){
                $vrednost = true;
                
                for($i = 1; $i<=$sablonDimenzija[0]; $i++){
                    $this->dodajOtvorenoPolje($nazivPolja . $i); // dodajemo sva neotvorena podpolja kao otvorena jer je resenje tacno
                }
                
                break;
            }
        }
        
        if($this->otvorena_polja === '' && is_bool($vrednost)){
            $this->otvorena_polja .= $nazivPolja;
        }else if($vrednost === true){
            $this->otvorena_polja .= ', ' . $nazivPolja;
        }
        
        return $this->save() ? $this : 0;
        
    }
    
    private function konvertujPoljeUBroj($nazivPolja ,$duzina){ //prekopirano iz views/igra/index fajla izmenjen regex
     $polje = substr($nazivPolja, 0, 1);
        
        
     //$polje = preg_split('/([\D]?)/', $nazivPolja, 0,PREG_SPLIT_DELIM_CAPTURE); 
     //izracunamo vrednost slova kao a = 0, b = 1... pa onda dodamo broj koji 
     //oznacava redosled polja da bi dobili poziciju tog polja iz kolone
     //"pojmovi ids" u tabeli "asocijacija"
     $vrednost = ((ord(strtolower($polje)) - 96) * ($duzina[0] + 1));
    
     return $vrednost;
    }
    
    public function proveriKonacnoResenje($unosKorisnika, $nosilac, &$ResenaIgra){
        if(strstr($this->otvorena_polja, 'resenje') !== false)
                return 0;
        foreach($nosilac->nizPojmova as $pojam){
            
            if ($pojam['id'] == $nosilac->RedosledPojmova[0] 
                    &&  (strcasecmp($pojam['sadrzaj'], $unosKorisnika)) === 0){
                
                if($this->otvorena_polja === ''){
                     $this->otvorena_polja .= 'resenje';
                 }else{
                     $this->otvorena_polja .= ', ' . 'resenje';
                 }
                 $ResenaIgra->resene_asocijacije = strval(intval($ResenaIgra->resene_asocijacije) + 1);
                 $ResenaIgra->save();
                return $this->save() ? 1 : 0; // vracamo int 1 ako je resenje tacno i potvrdjeno
            }
        }
    }
}
