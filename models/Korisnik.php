<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "korisnik".
 *
 * @property string $id
 * @property string $korisnicko_ime
 * @property string $lozinka
 * @property string $email
 * @property string $reset_kod
 * @property string $auth_key
 * @property int    $aktivan
 */
class Korisnik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $lozinka_uneta; // za prikaz trenutne lozinke kod admina i korisnika
    
    public static function tableName()
    {
        return 'korisnik';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['korisnicko_ime', 'lozinka', 'email'], 'required'],
            [['reset_kod', 'auth_key'], 'string'],
            [['korisnicko_ime', 'email'], 'string', 'max' => 30],
            [['lozinka'], 'string', 'max' => 60],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['lozinka_uneta'], 'string'],
            [['lozinka_uneta'],'required', 'on' => 'kreiranjeKorisnika']
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'korisnicko_ime' => 'Korisnicko Ime',
            'lozinka' => 'Lozinka',
            'email' => 'Email',
            'reset_kod' => 'Reset Kod',
            'auth_key' => 'Auth Key',
            'aktivan' => 'Aktivan',
            'lozinka_uneta' => 'Lozinka'
        ];
        
    }
    
    public function scenarios() {
       $scenariji = parent::scenarios();
       $scenariji['signup'] = ['korisnicko_ime', 'email'];
       $scenariji['promenaPodatakaAdmin'] = ['korisnicko_ime', 'email', 'lozinka_uneta', 'aktivan'];
       $scenariji['kreiranjeKorisnika'] = ['korisnicko_ime', 'email', 'lozinka_uneta', 'aktivan'];
       return $scenariji;
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->aktivan = 0;
            }
            return true;
        }
        return false;
    }
    
    public function noviAuthKod(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    public function noviResetKod(){
        $this->reset_kod = \yii::$app->security->generateRandomString();
    }
    
    public function novaLozinka($lozinka){
        $this->lozinka = \yii::$app->security
                    ->generatePasswordHash($lozinka);
    }
    
    public function vratiSveKorisnikeSemSebe($korisnik_id){
        $query = self::find()->select('')->where("id <> $korisnik_id");
        return (new \yii\data\ActiveDataProvider([
            'query' => $query
        ]));
    }
    
    public function vratiPokusajLogovanja(){
        if($this->hasOne(PokusajiLogovanja::className()
                , ['id' => 'id'])->one() === null){
                    (new PokusajiLogovanja())->ubaciUBazu($this->id);
                }
       return $this->hasOne(PokusajiLogovanja::className()
                , ['korisnik_id' => 'id'])->one();
    }
}
