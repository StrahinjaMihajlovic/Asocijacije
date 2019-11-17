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
        
        $modelResena_igra = $Igra && !\yii::$app->request->post('novaIgra', false)
                ? (new \app\models\ResenaIgra())
                ->vratiResenuIgru($Igra, $korisnik_id)
                : $this->poveziNepovezanuIgru($igra, $korisnik_id);
        
        if($modelResena_igra === false){
            return \yii::$app->request->isAjax ? $this->renderAjax('sveResene')
                    : $this->render('sveResene');
        }
        
        if($Igra !== $modelResena_igra->igra_id){
            return $this->redirect(\yii\helpers\Url::to(['igra/index', 'Igra' => $modelResena_igra->igra_id]));
        }
        
         if(!$Igra){
            return $this->redirect(
                    ['igra/index', 'Igra' => $modelResena_igra->igra->id]);
        }
        
        $sablon_igreDimenzije = (new SablonIgre())
                ->vratiSablon($igra->vratiIgru($modelResena_igra->igra->id)->sablon_igre_id)
                ->vratiSablonKaoNiz();
        
        $pojam = new \app\models\Pojam();
        $polje = $igra->traziPolja($modelResena_igra->igra->id);
        
        $TrenutnaAsocijacija = $this->vratiAsocijaciju( // odaberi odredjenu asocijaciju
                  $modelResena_igra->igra->id
                , intval($modelResena_igra->resene_asocijacije));
        
        $resenaAsocijacija = (new \app\models\ResenaAsocijacija()) //napravi ili preuzmi model resene asocijacije
                ->proveriVezu($TrenutnaAsocijacija->asocijacija_id
                 , $korisnik_id );
        
        $Nosilac = $pojam->traziPojmove( // vraca objekat klase "NosilacPodataka" u modelu "Pojam"
                $TrenutnaAsocijacija->asocijacija_id);
        
        if(\yii::$app->request->post('kliknuto', false)){
            $this->Kliknuto(\yii::$app->request->post('kliknuto'), $resenaAsocijacija);
        }
       
        if(\yii::$app->request->post('polje', false) && \yii::$app->request->post('unos', false)){
            $unos = [\yii::$app->request->post('polje') , \yii::$app->request->post('unos')];
            if($unos[0] === 'Resenje'){
                $resenaAsocijacija->proveriKonacnoResenje($unos[1]
                        , $Nosilac, $modelResena_igra
                 );
            }else{
            $this->otvoriPodResenje($unos[0], $unos[1]
                    , $resenaAsocijacija, $sablon_igreDimenzije, $Nosilac);
            }
        }
        if(\yii::$app->request->isAjax){/*
            return $this->renderAjax('index'
                ,['modelPolje' =>  $polje, 'nizPojam' => $Nosilac, 'modelResAsoc'
                    => $resenaAsocijacija, 'sablonDimenzije' => $sablon_igreDimenzije[0]
                , 'modelIgra' => $igra->vratiIgru($modelResena_igra->igra->id)]);*/
            return $this->refresh();
        }
        
        return $this->render('index'
                ,['modelPolje' =>  $polje, 'nizPojam' => $Nosilac, 'modelResAsoc'
                    => $resenaAsocijacija, 'sablonDimenzije' => $sablon_igreDimenzije[0]
                , 'modelIgra' => $igra->vratiIgru($modelResena_igra->igra->id)]);
    }
    
    public function actionMojeigre(){
        $reseneIgreModeli = (new \app\models\ResenaIgra)->vratiSveReseneIgreModeli(\yii::$app->user->id);
        $igraAsocijacijeObjekt = new \app\models\IgraAsocijacija();
        $sopstveneIgre = $this->vratiSopstveneIgre();
        return $this->render('mojeIgre',['reseneIgreModeli' => $reseneIgreModeli
                ,'igraAsocijacije' => $igraAsocijacijeObjekt
                , 'sopstveneIgre' =>$sopstveneIgre]);
    }
     
    private function vratiSopstveneIgre(){
        return (new Igra)->vratiSopstveneIgre(\yii::$app->user->id);
    }
    
    private function poveziNepovezanuIgru($igra, $korisnik_id){
        $nepovezanaIgra = $igra->vratiNepovezaneIgre($korisnik_id)
                ->getModels();
        //prvo uzimamo sve igre koje nisu povezane sa trenutnim korisnikom i onda
        // izaberemo slucajno jednu od njih.
        if(empty($nepovezanaIgra)){
            return false;
        }
        
        
        $nepovezanaIgra = $nepovezanaIgra[array_rand($nepovezanaIgra)];
        
        $modelResena_igra = new \app\models\ResenaIgra;
        $modelResena_igra->kreirajVezu($korisnik_id
                , $nepovezanaIgra->id);
        return $modelResena_igra;
    }
    
    private function vratiAsocijaciju($igraId, $nizResAsoc){ // vraca asocijaciju povezanu sa igrom
        $IgraAsoc = new IgraAsocijacija();
        
        return \yii::$app->request->post('dalje') 
                ? $IgraAsoc->vratiAsocPovezanuSaIgrom($igraId, $nizResAsoc)
                : $IgraAsoc->vratiAsocPovezanuSaIgrom($igraId, $nizResAsoc, true);
    }
    
    private function otvoriPodResenje($nazivPolja, $unosKorisnika, &$resenaAsocijacijaModel, $sablonDimenzija, $nosilac){
        return $resenaAsocijacijaModel->proveriPodResenjeIDodaj($nazivPolja, $unosKorisnika, $sablonDimenzija, $nosilac);
    }
    
    private function Kliknuto($nazivPolja, &$resenaAsocijacijaModel){
       
        $resenaAsocijacijaModel->dodajOtvorenoPolje($nazivPolja);
        
    }
}
