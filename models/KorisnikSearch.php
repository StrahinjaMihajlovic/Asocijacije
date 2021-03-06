<?php
namespace app\models;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class KorisnikSearch extends Korisnik{
    public function rules(){
        return [
            [['korisnicko_ime', 'email', 'prebivaliste'],'string'],
            [['aktivan'], 'integer']
        ];
    }
    /*@overload*/
    public function searchSemSebe($korisnikId,$params){
        $query = Korisnik::find();
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        $query->andWhere('id <> '. $korisnikId);
        //uciraj unete podatke sa gridview-a i validiraj ih
        if(!($this->load($params) && ($this->validate()))){
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'korisnicko_ime', $this->korisnicko_ime]);
        $query->andFilterWhere(['like', "email", $this->email]);
        $query->andFilterWhere(['like', "prebivaliste", $this->prebivaliste]);
        $query->andFilterWhere(['aktivan' => $this->aktivan]);
        return $dataProvider;
    }
}