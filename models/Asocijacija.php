<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asocijacija".
 *
 * @property string $id
 * @property string $resenje_id
 * @property int $kreator_id
 * @property DateTime $datum_kreiranja
 * @property Korisnik $kreator
 * @property Pojam $resenje
 * @property Pojam[] $pojams
 * @property IgraAsocijacija[] $igraAsocijacijas
 * @property Igra[] $igras
 * @property ResenaAsocijacija[] $resenaAsocijacijas
 * @property Korisnik[] $korisniks
 */
class Asocijacija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asocijacija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kreator_id'], 'required'],
            [['kreator_id'], 'integer'],
            [['datum_kreiranja'], 'datetime', 'format' => 'yyyy-MM-dd HH:mm:ss'],
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
            'datum_kreiranja' => 'Datum kreiranja'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKreator()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'kreator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResenje()
    {
        return $this->hasOne(Pojam::className(), ['id' => 'resenje_id']);
    }

    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPojams()
    {
        return $this->hasMany(Pojam::className(), ['id' => 'id_pojma'])->viaTable('pojam_polje_asocijacija', ['id_asocijacije' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgraAsocijacijas()
    {
        return $this->hasMany(IgraAsocijacija::className(), ['asocijacija_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgras()
    {
        return $this->hasMany(Igra::className(), ['id' => 'igra_id'])->viaTable('igra_asocijacija', ['asocijacija_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResenaAsocijacijas()
    {
        return $this->hasMany(ResenaAsocijacija::className(), ['asocijacija_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKorisniks()
    {
        return $this->hasMany(Korisnik::className(), ['id' => 'korisnik_id'])->viaTable('resena_asocijacija', ['asocijacija_id' => 'id']);
    }
    
    public function dodajAsocijacijuUBazi($pojmoviIds, $korisnikId){
        $this->setAttribute('kreator_id' , $korisnikId);
        $rez = $this->save();
        return $rez ? $this : false;
    }
    
    public function vratiSveAsocijacijeIgre($igraId){
        $query = self::find()->select('asocijacija.*')->from('asocijacija')
                ->leftJoin('igra_asocijacija', 'asocijacija.id = asocijacija_id')
                ->where('igra_id = ' . $igraId)->orderBy('id');
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        return $provider;
    }
    
    public function vratiVezuPojamAsocijacijaPolje($idAsoc = false){
        if($idAsoc){
           return PojamPoljeAsocijacija::findAll(['id_asocijacije' => $idAsoc]);
        }
        return $this->hasMany(PojamPoljeAsocijacija::class, ['id_asocijacije' => 'id']);
    }
    
    public function azurirajTrenAsoc($pojmoviIds){
        $this->datum_kreiranja = date('Y-m-d H:i:s');
        $igraModel = $this->igras[0];
        $igraModel->aktivna = 0;
        return $this->save() && $igraModel->save() ? $this : $this;
    }
    
    protected function srediIdPojmovaKaoTekst($pojmoviIds){
        $pojmoviIdsTekst = '';
        foreach ($pojmoviIds as $pojamTekst){
            $pojmoviIdsTekst .= $pojmoviIdsTekst === '' ? $pojamTekst: ', ' .$pojamTekst; 
        }
        return $pojmoviIdsTekst;
    }
}
