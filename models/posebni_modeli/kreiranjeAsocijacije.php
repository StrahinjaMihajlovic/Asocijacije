<?php
namespace app\models\posebni_modeli;

use Yii;
use \app\models\Pojam;
use \app\models\IgraAsocijacija;




/**
 * Description of kreiranjeAsocijacije
 *
 * @author strahinja
 */




class kreiranjeAsocijacije extends \yii\base\Model{
   public $sadrzajPoljaNiz;
   public $igra;
   public $asocijacija;
   //koristi se u slucaju da se manipulise postojecim asocijacama u nekoj igri
   public $AsocijacijeUIgri; 
   public $popunjenaPolja; 
   
   public function rules() {
      return [
            /*['sadrzajPoljaNiz', 'validatePolja'],*/
            
        ];
   }
   
   public function validatePolja($attribute, $params, $validator){
      /* foreach ($this->$attribute as $sadrzaj){
            $pojam = Pojam::find()->select('*')->from('pojam')
                    ->where('LOWER(sadrzaj) = \'' . strtolower($sadrzaj) .'\'')->one()->getAttribute('id');
            if($pojam === null){
                return ;     
            }
       }
       $this->addError($attribute, 'Ista takva asocijacija vec postoji');*/
   }
   
   public function stvoriAsocijacijuUBazi($korisnikId){
       $pojamModel = new Pojam();
       $pojamNizIds = array();
       
       if(array_search('', $this->sadrzajPoljaNiz) !== false){//proverava da li su sva polja uneta, privremeno, ukloniti posle
           return false;
       }
       
       foreach ($this->sadrzajPoljaNiz as $sadrzaj){
           $postoji =$pojamModel->daLiPostojiSadrzajIDodaj($sadrzaj, $korisnikId);
           
           if($postoji){
               array_push($pojamNizIds, $postoji);
           }else{
               return false;
           }
       }
       $rezultatAsoc = $this->asocijacija->dodajAsocijacijuUBazi($pojamNizIds, $korisnikId);
       //vraca true ako su se i asocijacija i veza igre i asocijacije napravile u bazi podataka
       return (new IgraAsocijacija())->napraviUBazi
               ($this->igra->id, $this->asocijacija->id) && ($rezultatAsoc);
   }
   
   public function nadjiRedAsocijacije($trenAsoc, $dugme){
       $rez = false;
       switch($dugme){
           case 'nazad':
              $rez = ($trenAsoc !== false && $trenAsoc > 0) ? $this->AsocijacijeUIgri
                   [array_search($trenAsoc, array_column($this
                                ->AsocijacijeUIgri, 'id')) - 1]['id']
                         : $this->AsocijacijeUIgri
                    [array_key_last($this->AsocijacijeUIgri)]['id'];
            /*dodati proveru kljuca id od asocijacije, 
                          * ne moze ici manje od 0*/
               break;
           case 'napred':
               $rez = ($trenAsoc !== false && $trenAsoc !== $this
                    ->AsocijacijeUIgri[array_key_last($this
                            ->AsocijacijeUIgri)]['id']) 
                    ? $this->AsocijacijeUIgri[array_search($trenAsoc
                            , array_column($this
                                ->AsocijacijeUIgri, 'id')) + 1]['id']
                         : false;
               break;
       }
       return $rez;
   }
    
}
