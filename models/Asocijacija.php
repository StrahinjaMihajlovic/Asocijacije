<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asocijacija".
 *
 * @property string $id
 * @property string $resenje_id
 * @property int $kreator_id
 * @property string $pojmovi_ids
 *
 * @property Korisnik $kreator
 * @property Pojam $resenje
 * @property AsocijacijaPojam[] $asocijacijaPojams
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
            [['resenje_id', 'kreator_id', 'pojmovi_ids'], 'required'],
            [['resenje_id', 'kreator_id'], 'integer'],
            [['pojmovi_ids'], 'string', 'max' => 255],
            [['pojmovi_ids'], 'unique'],
            [['kreator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['kreator_id' => 'id']],
            [['resenje_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pojam::className(), 'targetAttribute' => ['resenje_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resenje_id' => 'Resenje ID',
            'kreator_id' => 'Kreator ID',
            'pojmovi_ids' => 'Pojmovi Ids',
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
    public function getAsocijacijaPojams()
    {
        return $this->hasMany(AsocijacijaPojam::className(), ['asocijacija_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPojams()
    {
        return $this->hasMany(Pojam::className(), ['id' => 'pojam_id'])->viaTable('asocijacija_pojam', ['asocijacija_id' => 'id']);
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
        $pojmoviIdsTekst = $this->srediIdPojmovaKaoTekst($pojmoviIds);
        $this->setAttribute('kreator_id' , $korisnikId);
        $this->setAttribute('pojmovi_ids', $pojmoviIdsTekst);
        $this->resenje_id = $pojmoviIds[0];
        return $this->save() ? $this : false;
    }
    
    public function vratiSveAsocijacijeIgre($igraId){
        $query = self::find()->select('asocijacija.*')->from('asocijacija')
                ->leftJoin('igra_asocijacija', 'asocijacija.id = asocijacija_id')
                ->where('igra_id = ' . $igraId)->orderBy('id');
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        return $provider->getModels();
    }
    
    public function azurirajTrenAsoc($pojmoviIds){
        $pojmoviIdsTekst = $this->srediIdPojmovaKaoTekst($pojmoviIds);
        $this->setAttribute('pojmovi_ids', $pojmoviIdsTekst);
        return $this->save() ? $this : false;
    }
    
    protected function srediIdPojmovaKaoTekst($pojmoviIds){
        $pojmoviIdsTekst = '';
        foreach ($pojmoviIds as $pojamTekst){
            $pojmoviIdsTekst .= $pojmoviIdsTekst === '' ? $pojamTekst: ', ' .$pojamTekst; 
        }
        return $pojmoviIdsTekst;
    }
}
