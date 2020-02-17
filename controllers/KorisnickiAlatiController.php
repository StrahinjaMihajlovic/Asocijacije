<?php
namespace app\controllers;
use app\models\Polje;
use \app\models\SablonIgre;
use app\models\posebni_modeli\kreiranjeAsocijacije;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 * @var $kreiranjeAsocijacije \app\models\posebniModeli\kreiranjeAsocijacije */

class KorisnickiAlatiController extends \yii\web\Controller
{
    
    public function behaviors() {
        parent::behaviors();
        return [
           'access' => [
               'class' => \yii\filters\AccessControl::class,
               'rules' => [
                   [
                       'allow' => false,
                       'roles' => ['?']
                   ],
                   [
                       'allow' => true,
                       'roles' => ['@']
                   ]
               ]
           ]
        ];
    }
    
    public function actionKreiranjeigre(){
        $igra = new \app\models\Igra();
        $kategorija = new \app\models\Kategorija();
        
        if(\yii::$app->request->post('Igra')){
            $igra->attributes = \yii::$app->request->post('Igra'); 
            //probaj kreirati igru u bazi i ako ne uspe, ostani na stranici, u suprotnom idi na intefejz za kreiranje 
            //asocijacija
            return $igra->stvoriIgruUBazi(\yii::$app->user->id) ? $this->redirect(\yii\helpers\Url::to(['kreiranjeasocijacije'
                    , 'trenIgra' => $igra->getAttribute('id'), 'nova' => true])) 
                    : $this->render('kreiranjeIgre', ['igra' => $igra ,'kategorije' 
                    => $kategorija->vratiKategorijeNiz()]);
        }
        
        return $this->render('kreiranjeIgre', ['igra' => $igra ,'kategorije' => $kategorija->vratiKategorijeNiz()]); 
    }
    
    public function actionKreiranjeasocijacije($trenIgra = false, $nova = false, $trenAsoc = false){
        if($nova && $trenIgra > 0){
            return $this->redirect(\yii\helpers\Url::to(['kreiranjeasocijacije' , 'trenIgra' => $trenIgra]));
        }
        if($trenIgra === false){
            return $this->redirect(\yii\helpers\Url::to(['kreiranjeigre']));
        }
        
        /*$nizPolja = (new Polje())->vratiSvaPoljaSablona($sablon->id);
        $pojam = new \app\models\Pojam();*/
        $igra = (new \app\models\Igra())->vratiIgru($trenIgra);
        
        
        $kreiranjeAsocijacije = new kreiranjeAsocijacije();
        $kreiranjeAsocijacije->igra = $igra;
        $kreiranjeAsocijacije->sablon = (new SablonIgre())->vratiSablon(SablonIgre::find()->one()->id);
        $kreiranjeAsocijacije->asocijacija = (new \app\models\Asocijacija());
        $kreiranjeAsocijacije->AsocijacijeUIgri = $kreiranjeAsocijacije
                ->asocijacija->vratiSveAsocijacijeIgre($igra->id)->getModels();
        
        if(\yii::$app->request->post('nazad' , 0) === '1'){ 
            /*ako je dugme nazad kliknuto, izvrsi preusmerenje sa get 
             * parametrima u skladu sa koje asocijacije je korisnik dosao*/
            $trenAsoc = $kreiranjeAsocijacije
                    ->nadjiRedAsocijacije($trenAsoc, 'nazad');
            return $this->redirect(\yii\helpers\Url::to(
                    ['kreiranjeasocijacije' , 'trenIgra' 
                         => $trenIgra, ('' . $trenAsoc !== false 
                            ? 'trenAsoc' : '') => $trenAsoc]));
        }else if(\yii::$app->request->post('napred' , 0) === '1'){
            
             $trenAsoc = $kreiranjeAsocijacije
                    ->nadjiRedAsocijacije($trenAsoc, 'napred');
            return $this->redirect(\yii\helpers\Url::to(
                     ['kreiranjeasocijacije' , 'trenIgra' => $trenIgra
                    , ('' . $trenAsoc !== false ? 'trenAsoc' : '') 
                         => $trenIgra, 'trenAsoc' => $trenAsoc]));
        }
        
        $nizPolja = array();
        if($trenAsoc){//popunjavamo polja sa vezom pojam_polje_asocijacija
            $nizPolja = (\app\models\PojamPoljeAsocijacija::findAll(['id_asocijacije'
            => intval($trenAsoc)]));
            $kreiranjeAsocijacije->popunjenaPolja = $nizPolja;
            $kreiranjeAsocijacije->asocijacija = (new \app\models\Asocijacija)
                    ->findOne($trenAsoc);
        }
        
        
        $rezultatUpisaUBazu = '';
        if(\yii::$app->request->post('Polje',false)){ //upisujemo u bazu korisnicke izmene
            $kreiranjeAsocijacije->sadrzajPoljaNiz 
                    = \yii::$app->request->post('Polje')['naziv'];
            $rezultatUpisaUBazu = \yii::$app->request->get('trenAsoc', false) 
                    ? $kreiranjeAsocijacije
                    ->azurirajAsocUBazi(\yii::$app->user->id)
                    : $kreiranjeAsocijacije
                    ->stvoriAsocijacijuUBazi(\yii::$app->user->id);
            if(!$trenAsoc){
                unset($kreiranjeAsocijacije->asocijacija);
               
            }else{
                return $this->refresh();
            }
        }
        
        /*$nizPolja = (\app\models\PojamPoljeAsocijacija::findAll(['id_asocijacije'
            => isset($kreiranjeAsocijacije->asocijacija->id) ? $kreiranjeAsocijacije->asocijacija->id:0]));*/
        if(empty($nizPolja)){ //smestamo default prazne modele ako ne postoje ili ako je nova asocijacija
            foreach ($kreiranjeAsocijacije->sablon->poljes as $polje){
                $modelPojamPoljeAsoc = new \app\models\PojamPoljeAsocijacija();
                $modelPojamPoljeAsoc->id_polja = $polje->id;
                array_push($nizPolja, $modelPojamPoljeAsoc);
            }
        }
         
        
        return $this->render('kreiranjeAsocijacije', ['polja' => $nizPolja
            //',sablon' => $sablon, 'pojam' => $pojam
                , 'uspesnost' => $rezultatUpisaUBazu, 'nova' => $nova
                , 'kreiranjeAsocijacije' => $kreiranjeAsocijacije]);
    }
    
    public function actionMojProfil(){
        $korisnik = \app\models\Korisnik::findOne(\yii::$app->user->id);
        $korisnik->setScenario('promenaPodatakaKorisnik');
        $postData = \yii::$app->request->post('Korisnik', false);
        if(isset($postData) && $postData!==false){
            $korisnik->setAttributes($postData,false);
            if(isset($postData['lozinka_uneta']) && trim($postData['lozinka_uneta']) !== ''){
                $korisnik->novaLozinka($postData['lozinka_uneta']);
            }
            $korisnik->save();
            return $this->render('mojProfil',['korisnik' => $korisnik, 'uspesnost' => empty($korisnik->errors)]);
        }
        return $this->render('mojProfil',['korisnik' => $korisnik]);
    }
    
    public function actionAzuriranjeIgre($id){
        $igra = \app\models\Igra::findOne(intval($id));
        $igra->aktivna = 0;
        if($igra->load(\yii::$app->request->post()) && $igra->save(true, ['naziv', 'opis','kategorija_id', 'aktivna'])){
            $this->redirect(['kreiranjeasocijacije', 'trenIgra' => $igra->id]);
        }
    }
}