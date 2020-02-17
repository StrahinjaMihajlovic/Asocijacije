<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of IgraSearch
 *
 * @author strahinja
 */
class IgraSearch extends Igra{
    public function rules(){
        return [
            [['naziv','opis', 'kreator_id', 'kategorija_id'],'string'],
            [['aktivna'], 'integer']
        ];
    }
    
    public function search($params){
        $Igra = Igra::find()->select("igra.*, korisnik.korisnicko_ime as ime_autora, kategorija.naziv as naziv_kategorije");
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $Igra
        ]);
        
         $Igra->joinWith('kreator', false, 'left join');
        $Igra->joinWith('kategorija', false, 'left join');
        
        if(!($this->load($params) && ($this->validate()))){
            return $dataProvider;
        }
       
        
        $Igra->andFilterWhere(['like', 'igra.naziv', $this->naziv]);
        $Igra->andFilterWhere(['like', 'opis', $this->opis]);
        $Igra->andFilterWhere(['aktivna' => $this->aktivna]);
        $Igra->andFilterWhere(['like', 'kategorija.naziv', $this->kategorija_id]);
        $Igra->andFilterWhere(['like', 'korisnik.korisnicko_ime', $this->kreator_id]);
        
        return $dataProvider;
    }
}
