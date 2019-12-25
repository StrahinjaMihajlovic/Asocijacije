<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $model->getUser()->vratiPokusajLogovanja()->resetujBrojac();
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionSignup($kod = false){
        $korisnik = new \app\models\Korisnik();
        
        if(strlen($kod) > 32){
            $unos = str_split($kod,32);
            $korisnik = $korisnik->findOne($unos[1]);
            if($unos[0] === $korisnik->auth_key){
                $korisnik->aktivan = 1;
                $korisnik->noviAuthKod();
                if($korisnik->save()){
                    \yii::$app->user->login(\app\models\User::findOne($unos[1]));
                }
                return $this->redirect(\yii\helpers\Url::toRoute(['site/index']));
            }
        }
        
        
        $korisnik->scenario = 'signup';
        if($korisnik->load(\yii::$app->request->post())){
            $korisnik->novaLozinka($korisnik->lozinka);
            if(!$korisnik->save()){
                return $this->render('signup', ['model' => new \app\models\Korisnik()]);
            }
             \yii::$app->mailer->compose()->setFrom('strale.develop@gmail.com')
                    ->setTo($korisnik->email)->setSubject('Registracija')
                    ->setTextBody("Hvala na registraciji,\n "
                            . "Registrujte se na sledecem linku: " 
                            . (new \yii\web\UrlManager)->createAbsoluteUrl(['site/signup', 'kod' 
                                => $korisnik->auth_key.$korisnik->id]))->send();
            
            return $this->render('signup_uspeh');
        }
        return $this->render('signup', ['model' => new \app\models\Korisnik()]);
    }
    
    public function actionResetLozinke($kod = false){
        $unos = $kod ? str_split($kod,32)
                :\yii::$app->request->post('Korisnik', false);
        if(!$unos){ //korisnik zahtevai interfejs za reset lozinke
            return $this->render('reset-lozinke',['potvrdjeno' => 0, 'model' => new \app\models\Korisnik()]);
        }
        
        if(isset(\yii::$app->request->post('Korisnik')['lozinka'])){ // ako je nova lozinka uneta
            $korisnik = new \app\models\Korisnik();
            $korisnik = $korisnik->findOne($unos[1]); //link mora da je sa mail-a
            if($unos[0] === $korisnik->reset_kod){
                $korisnik->novaLozinka(\yii::$app->request->post('Korisnik')['lozinka']);
                $korisnik->noviResetKod();
                $korisnik->update(true, ['lozinka', 'reset_kod']);
                return $this->redirect(\yii\helpers\Url::to(['site/login']));
            }
            return $this->redirect(\yii\helpers\Url::to(['index']));
        }
        
        if($kod){ // kliknut je link sa korisnikov mail-a
            $korisnik = \app\models\User::findOne($unos[1]);
            if($korisnik->reset_kod === $unos[0]){
                return $this->render('reset-lozinke', ['potvrdjeno' => false
                    , 'novaLozinka' => true, 'model' => new \app\models\Korisnik()]);
            }
            return $this->render('reset-lozinke',['kodNevazeci' => true]);
        }
        
        $korisnik = \app\models\Korisnik::findOne(['email' => $unos]);
        if(!$korisnik->reset_kod){ //definise reset kod ako je null
            $korisnik->noviResetKod();
            $korisnik->update();
        }
        $uspesnost = \yii::$app->mailer->compose()->setFrom('strale.develop@gmail.com')
                    ->setTo($korisnik->email)->setSubject('Reset lozinke')
                    ->setTextBody("Zahtevali ste promenu lozinke,\n "
                            . "Resetujte lozinku na sledecem linku: " 
                            . (new \yii\web\UrlManager)->createAbsoluteUrl(['site/reset-lozinke', 'kod' 
                                => $korisnik->reset_kod . $korisnik->id]))->send();
        return $this->render('reset-lozinke', ['potvrdjeno' => $uspesnost]);
    }
}
