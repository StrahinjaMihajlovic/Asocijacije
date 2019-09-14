<?php


namespace app\controllers;
use app\models\Igra;
use app\models\IgraAsocijacija;
use app\models\Polje;
use app\models\SablonIgre;
 use yii\data\ActiveDataProvider;
 use yii\db\ActiveQuery;
 

class IgraController extends \yii\web\Controller
{
    
    public function actionIndex($Igra = FALSE){
        $igra = new Igra();
        $korisnik_id = \yii::$app->user->id 
                ? \yii::$app->user->id : 0;
        $session = \yii::$app->session;
        $session->destroy();
        $modelResena_igra = $Igra
                ? (new \app\models\ResenaIgra())
                ->vratiResenuIgru($Igra, $korisnik_id)
                : $this->poveziNepovezanuIgru($igra, $korisnik_id);
        if(!$Igra){
            return $this->redirect(
                    ['igra/index', 'Igra' => $modelResena_igra->igra->id]);
        }
        

        $sablon_igreDimenzije = (new SablonIgre())
                ->vratiSablon($igra->vratiIgru($modelResena_igra->igra->id)->getModels()[0]->sablon_igre_id)
                ->vratiSablonKaoNiz();
        
        $pojam = new \app\models\Pojam();
        $polje = $igra->traziPolja($modelResena_igra->igra->id);
        
        $TrenutnaAsocijacija = $this->vratiAsocijaciju( // odaberi odredjenu asocijaciju
                  $modelResena_igra->igra->id
                , $modelResena_igra->resene_asocijacije);
        
        $resenaAsocijacija = (new \app\models\ResenaAsocijacija()) //napravi ili preuzmi model resene asocijacije
                ->proveriVezu($TrenutnaAsocijacija->asocijacija_id
                 , $korisnik_id );
        
        $Nosilac = $pojam->traziPojmove( // vraca objekat klase "NosilacPodataka" u modelu "Pojam"
                $TrenutnaAsocijacija->asocijacija_id);
        
        if(\yii::$app->request->post('kliknuto', false)){
            $this->Kliknuto(\yii::$app->request->post('kliknuto'), $resenaAsocijacija); 
        }
        
        return $this->render('index'
                ,['modelPolje' =>  $polje, 'nizPojam' => $Nosilac, 'modelResAsoc'
                    => $resenaAsocijacija, 'sablonDimenzije' => $sablon_igreDimenzije[0]
                , 'idIgre' => $igra->vratiIgru($modelResena_igra->igra->id)]);
    }
    
    
     
    private function poveziNepovezanuIgru($igra, $korisnik_id){
        $nepovezanaIgra = $igra->vratiNepovezaneIgre($korisnik_id)
                ->getModels();
        //prvo uzimamo sve igre koje nisu povezane sa trenutnim korisnikom i onda
        // izaberemo slucajno jednu od njih.
        
        $nepovezanaIgra = $nepovezanaIgra[array_rand($nepovezanaIgra)];
        
        $modelResena_igra = new \app\models\ResenaIgra;
        $modelResena_igra->kreirajVezu($korisnik_id
                , $nepovezanaIgra->id);
        return $modelResena_igra;
    }
    
    private function vratiAsocijaciju($igraId, $nizResAsoc){ // vraca asocijaciju povezanu sa igrom
        $IgraAsoc = new IgraAsocijacija();
        return $IgraAsoc->vratiAsocPovezanuSaIgrom($igraId, $nizResAsoc);
    }
    
    private function Kliknuto($nazivPolja, &$resenaAsocijacijaModel){
       
        $resenaAsocijacijaModel->dodajOtvorenoPolje($nazivPolja);
    }
}
