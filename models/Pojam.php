<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pojam".
 *
 * @property string $id
 * @property int $kreator_id
 * @property string $sadrzaj
 *
 * @property Asocijacija[] $asocijacijas
 * @property AsocijacijaPojam[] $asocijacijaPojams
 * @property Asocijacija[] $asocijacijas0
 * @property Korisnik $kreator
 */

use yii\data\ActiveDataProvider;
class Pojam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pojam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kreator_id', 'sadrzaj'], 'required'],
            [['kreator_id'], 'integer'],
            [['sadrzaj'], 'string', 'max' => 50],
            [['sadrzaj'], 'unique'],
            [['kreator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['kreator_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kreator_id' => 'Kreator ID',
            'sadrzaj' => 'Sadrzaj',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacijas()
    {
        return $this->hasMany(Asocijacija::className(), ['resenje_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacijaPojams()
    {
        return $this->hasMany(AsocijacijaPojam::className(), ['pojam_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacijas0()
    {
        return $this->hasMany(Asocijacija::className(), ['id' => 'asocijacija_id'])->viaTable('asocijacija_pojam', ['pojam_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKreator()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'kreator_id']);
    }
   
    public function traziPojmove($id_asocijacije){  //trazi pojmove odabrane asocijacije
        $wrapper = new nosilacPodatka();
         $query = Asocijacija::find()->select('asocijacija.pojmovi_ids')
                 ->from('asocijacija')->where('id = '.$id_asocijacije);
         
        $provider = new ActiveDataProvider([
                'query' => $query,       
            ]);
         $nizId = $provider->getModels();
         $nizId = $nizId[array_rand($nizId)];
         
         $queryPojam = self::find()->select('*')->where('id in ('.$nizId->pojmovi_ids.')')
                 ->orderBy('id');
         $providerPojmova = new ActiveDataProvider([
             'query' => $queryPojam,
         ]);
         $nizPojmova = explode(',', trim($nizId->pojmovi_ids));
         $wrapper->RedosledPojmova = array_filter($nizPojmova, array(__CLASS__ , 'ukloni_zapete'));
         $wrapper->nizPojmova = $providerPojmova->getModels();
         
         return $wrapper;
        
    }
    
    public function vratiSadrzajPojma($id){
        return self::findOne($id)->getAttribute('sadrzaj');
    }
    
    private function ukloni_zapete($value){ //koristi se par linija gore, ne uklanjati!
        return $value === ',' ? false : true;
    }
    
    public function daLiPostojiSadrzajIDodaj($sadrzaj, $korisnikId){ //proverava da li vec postoji pojam sa istim sadrzajem i dodaje novi ako nije. U suprotnom vraca nadjeni
        $query = self::find()->select('id')->from('pojam')
                ->where('sadrzaj = \'' . $sadrzaj .'\'')->one();
        if($query === null){
            $modelPojma = new Pojam();
            $modelPojma->setAttributes(['kreator_id' => $korisnikId
                    , 'sadrzaj' => $sadrzaj], false);
            return $modelPojma->save() ? $modelPojma->getAttribute('id') : $modelPojma->errors;
        }
        return $query->getAttribute('id');
    }
}

class nosilacPodatka{
    public $RedosledPojmova;
    public $nizPojmova;
}