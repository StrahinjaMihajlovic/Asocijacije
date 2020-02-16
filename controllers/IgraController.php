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
        
        
        
         if(!$Igra){
            return $this->redirect(
                    ['igra/index', 'Igra' => $modelResena_igra->igra->id]);
        }
        
        $sablonIgre = (new SablonIgre())
                ->vratiSablon($igra->vratiIgru($modelResena_igra->igra->id)->sablon_igre_id);
        
        
        $TrenutnaAsocijacija = $this->vratiAsocijaciju( // odaberi odredjenu asocijaciju
                  $modelResena_igra->igra->id
                , intval($modelResena_igra->resene_asocijacije));
        $poljeVeza = $TrenutnaAsocijacija->vratiVezuPojamAsocijacijaPolje()->all();
        $resenaAsocijacija = (new \app\models\ResenaAsocijacija()) //napravi ili preuzmi model resene asocijacije
                ->proveriVezu($TrenutnaAsocijacija->id
                 , $korisnik_id );
       
        if(\yii::$app->request->post('sledecaAsoc', false) && $resenaAsocijacija->proveriDaLiJeResena()){ // predji na sledecu asocijaciju
            $modelResena_igra->resene_asocijacije++;
            $modelResena_igra->save();
            return $this->redirect(\yii\helpers\Url::to(['igra/index', 'Igra' => $modelResena_igra->igra_id]));
        }
        
        if(\yii::$app->request->post('kliknuto', false)){
            $this->Kliknuto(\yii::$app->request->post('kliknuto'), $resenaAsocijacija);
        }
       
        if(\yii::$app->request->post('polje', false) && \yii::$app->request->post('unos', false)){
            $unos = [\yii::$app->request->post('polje') , \yii::$app->request->post('unos')];
            if($unos[0] === 'Resenje'){
                $resenaAsocijacija->proveriKonacnoResenje($unos[1],$TrenutnaAsocijacija,$modelResena_igra
                 );
            }else{
            $this->otvoriPodResenje($unos[0], $unos[1]
                    , $resenaAsocijacija, $poljeVeza);
            }
        }
        
        if(\yii::$app->request->isAjax){
            return $this->renderAjax('index'
                ,['modelPoljeVeza' =>  $poljeVeza, 'modelResAsoc'
                    => $resenaAsocijacija, 'sablonIgre' => $sablonIgre
                , 'modelIgra' => $igra->vratiIgru($modelResena_igra->igra->id)
                    , 'modelResIgra' => $modelResena_igra]);
           
        }
        
        return $this->render('index'
                ,['modelPoljeVeza' =>  $poljeVeza,'modelResAsoc'
                    => $resenaAsocijacija, 'sablonIgre' => $sablonIgre
                , 'modelIgra' => $igra->vratiIgru($modelResena_igra->igra->id),
                    'modelResIgra' => $modelResena_igra]);
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
                , intval($nepovezanaIgra->id));
        if($modelResena_igra){
            $nepovezanaIgra->broj_igranja++;
            $nepovezanaIgra->save();
        }
        return $modelResena_igra;
    }
    
    private function vratiAsocijaciju($igraId, $nizResAsoc){ // vraca asocijaciju povezanu sa igrom
        $IgraAsoc = new IgraAsocijacija();
        
        return \yii::$app->request->post('dalje') 
                ? $IgraAsoc->vratiAsocPovezanuSaIgrom($igraId, $nizResAsoc)
                : $IgraAsoc->vratiAsocPovezanuSaIgrom($igraId, $nizResAsoc, true);
    }
    
    private function otvoriPodResenje($nazivPolja, $unosKorisnika, &$resenaAsocijacijaModel, $veza){
        $poljeModel = Polje::findOne(['naziv' => $nazivPolja]);
        return $resenaAsocijacijaModel->proveriPodResenjeIDodaj($poljeModel->id, $unosKorisnika, $veza);
    }
    
    private function Kliknuto($nazivPolja, &$resenaAsocijacijaModel){
       $poljeModel = Polje::findOne(['naziv' => $nazivPolja]);
        $resenaAsocijacijaModel->dodajOtvorenoPolje($poljeModel->id);
        
    }
}
