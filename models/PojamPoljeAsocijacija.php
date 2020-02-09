<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pojam_polje_asocijacija".
 *
 * @property int|null $id_asocijacije
 * @property int|null $id_polja
 * @property int|null $id_pojma
 *
 * @property Asocijacija $asocijacije
 * @property Pojam $pojma
 * @property Polje $polja
 */
class PojamPoljeAsocijacija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pojam_polje_asocijacija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_asocijacije', 'id_polja', 'id_pojma'], 'integer'],
            [['id_asocijacije'], 'exist', 'skipOnError' => true, 'targetClass' => Asocijacija::className(), 'targetAttribute' => ['id_asocijacije' => 'id']],
            [['id_pojma'], 'exist', 'skipOnError' => true, 'targetClass' => Pojam::className(), 'targetAttribute' => ['id_pojma' => 'id']],
            [['id_polja'], 'exist', 'skipOnError' => true, 'targetClass' => Polje::className(), 'targetAttribute' => ['id_polja' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_asocijacije' => 'Id Asocijacije',
            'id_polja' => 'Id Polja',
            'id_pojma' => 'Id Pojma',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacija()
    {
        return $this->hasOne(Asocijacija::className(), ['id' => 'id_asocijacije']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPojam()
    {
        return $this->hasOne(Pojam::className(), ['id' => 'id_pojma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolje()
    {
        return $this->hasOne(Polje::className(), ['id' => 'id_polja']);
    }
    
    public static function stvoriVeze($asocijacija_id, $modeliPolja, $nizPojmova){
        foreach ($nizPojmova as $kljucPojma => $pojam ){
            //dodati dve razlicite procedure u zavisnosti od tipa nizPojmova
            $veza = new PojamPoljeAsocijacija();
            $veza->setAttributes([
                'id_asocijacije' => $asocijacija_id,
                'id_pojma' => $pojam,
                'id_polja' => $modeliPolja[$kljucPojma]->id,
            ]);
            if(!$veza->save()){
                return false;
            }
        }
        return true;
        
    }
    
    public static function vratiSpecVezu($idAsoc,$idPolja, $idPojma = ''){
        return self::find()->andFilterWhere(['id_asocijacije'=> $idAsoc ,'id_pojma' => $idPojma, 'id_polja' =>  $idPolja])->one();
    }
    
    public static function vratiSveVezePoKriterijumu($idAsoc = '',$idPolja = '', $idPojma = '',  $like = false){
        $query = self::find();
        if($like){
            $query->andFilterWhere(['like',[
                    'id_asocijacije',
                    'id_pojma' ,
                    'id_polja'
                ], 
                [
                    $idAsoc,
                    $idPojma,
                    $idPolja
                ]
            ]);
                /*->andFilterWhere(['ilike' , 'id_pojma',$idPojma])
                -->andFilterWhere(['ilike' , 'id_polja',$idPolja]);*/
        }else{
            $query->andFilterWhere([
                'id_asocijacije' => $idAsoc,
                'id_pojma' => $idPojma,
                'id_polja' => $idPolja
            ]);
        }
        $rez = $query->all();
        return $query->all();
    }
    
    public static function vratiSveVezePolja($nazivPolja, $idAsoc){//pozivamo kad korisnik odgovori na jedno od glavnih polja, vraca sva podpolja
        $query = self::find();
        $query->joinWith('polje');
       /* $query->andWhere([ 'id_asocijacije',$idAsoc ? $idAsoc : ''])
                ->andWhere(['ilike' , 'id_pojma',$nazivPolja ? $nazivPolja : '']);*/
        $query->where("id_asocijacije = $idAsoc");
        $query->andWhere(['like','polje.naziv', $nazivPolja.'%', false]); //vrati sva polja stila A, A1, A2 za $nazivPolja=A
        //false zbog znaka %
        return $query->all();
                
    }
}
