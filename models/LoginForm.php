<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_zabranjenoLogovanje;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if(!$user){
                 $this->addError($attribute, 'Pogresna lozinka ili korisnicko ime.');
            }elseif (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Pogresna lozinka ili korisnicko ime.');
                $user->vratiPokusajLogovanja()->promeniBroj();
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->proveriAktiviranost() && !$this->getZabranjenoLogovanje()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        
        return false;
    }

    public function proveriAktiviranost(){ //aktiviranost korisnika, neke radnje treba da se zabrane. Koristi se u login funkciji gore
        if($this->getUser()->aktivan)
            return true;
        return false;
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            if(filter_var($this->username, FILTER_VALIDATE_EMAIL) !== false){
                $this->_user = User::findOne(['email' => $this->username]);
            }else{
                $this->_user = User::findByUsername($this->username);
            }    
        }

        return $this->_user;
    }
    
    public function getZabranjenoLogovanje(){
        if($this->getUser() === null){
            return false;
        }
        
        $pokusajLogovanjaModel = $this->getUser()->vratiPokusajLogovanja();
        $datumStamp = time();
        
        if($pokusajLogovanjaModel->getAttribute('broj_pokusaja') >= 3 
                && ($datumStamp - strtotime($pokusajLogovanjaModel
                        ->getAttribute('vreme_zadnjeg')) ) < 30){
            $this->_zabranjenoLogovanje = true;
        }else if($pokusajLogovanjaModel->getAttribute('broj_pokusaja') >=3){
            $this->_zabranjenoLogovanje = false;
        }else{
            $this->_zabranjenoLogovanje = false;
        }
        return $this->_zabranjenoLogovanje;
    }
}
