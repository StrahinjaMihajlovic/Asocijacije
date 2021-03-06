<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "igra".
 *
 * @property string $id
 * @property string $kategorija_id
 * @property int $kreator_id
 * @property string $naziv
 * @property string $opis
 * @property int $aktivna
 * @property int $broj_igranja
 * @property string $sablon_igre_id
 *
 * @property Kategorija $kategorija
 * @property Korisnik $kreator
 * @property SablonIgre $sablonIgre
 * @property IgraAsocijacija[] $igraAsocijacijas
 * @property Asocijacija[] $asocijacijas
 * @property ResenaIgra[] $resenaIgras
 * @property Korisnik[] $korisniks
 */
use yii\data\ActiveDataProvider;
class Igra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'igra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kategorija_id', 'naziv'], 'required'],
            [['kategorija_id', 'kreator_id', 'aktivna', 'broj_igranja', 'sablon_igre_id'], 'integer'],
            [['opis'], 'string'],
            [['naziv'], 'string', 'max' => 100],
            [['kategorija_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kategorija::className(), 'targetAttribute' => ['kategorija_id' => 'id']],
            [['kreator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['kreator_id' => 'id']],
            [['sablon_igre_id'], 'exist', 'skipOnError' => true, 'targetClass' => SablonIgre::className(), 'targetAttribute' => ['sablon_igre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kategorija_id' => 'Kategorija ID',
            'kreator_id' => 'Kreator ID',
            'naziv' => 'Naziv',
            'opis' => 'Opis',
            'aktivna' => 'Aktivna',
            'broj_igranja' => 'Broj Igranja',
            'sablon_igre_id' => 'Sablon Igre ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategorija()
    {
        return $this->hasOne(Kategorija::className(), ['id' => 'kategorija_id']);
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
    public function getSablonIgre()
    {
        return $this->hasOne(SablonIgre::className(), ['id' => 'sablon_igre_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgraAsocijacijas()
    {
        return $this-hasMany(IgraAsocijacija::className(), ['igra_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacijas()
    {
        return $this->hasMany(Asocijacija::className(), ['id' => 'asocijacija_id'])->viaTable('igra_asocijacija', ['igra_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResenaIgras()
    {
        return $this->hasMany(ResenaIgra::className(), ['igra_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKorisniks()
    {
        return $this->hasMany(Korisnik::className(), ['id' => 'korisnik_id'])->viaTable('resena_igra', ['igra_id' => 'id']);
    }
    
    public function  trazi(){
        $query = Igra::find()->select('*')->from('igra');
        
        $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                    ],
            ]);
         return $provider;
    }
    
    public function traziPolja($id_igre = false){
        if($id_igre){
        $query = Igra::find()->select('polje.naziv')->from('polje')
                ->innerJoin('igra','polje.sablon_igre_id = igra.sablon_igre_id')
                ->where('igra.id = ' . strval($id_igre));
        }else{
         $query = Igra::find()->select('polje.naziv')->from('polje')
                ->innerJoin('igra','polje.sablon_igre_id = igra.sablon_igre_id')
                ->where('igra.id = ' . $this->id);
        }
        $provider = new ActiveDataProvider([
                'query' => $query,
                
            ]);
            
         return $provider;
    }
    
    public function vratiNepovezaneIgre($korisnik_id){ // biramo igru koju igrac nije pre igrao i koja nije njegova
        $query = Igra::find()->select('*')->from('igra')->leftJoin('resena_igra', 'igra.id ='
                . 'resena_igra.igra_id')->where('not exists(select '
                        . "* from resena_igra where resena_igra.korisnik_id = $korisnik_id and igra_id = igra.id)"
                        .' and igra.kreator_id <> ' . $korisnik_id
                        . ' and igra.aktivna > 0');
        
         $provider = new ActiveDataProvider([
                'query' => $query,
                
            ]);
            
         return $provider;
    }
    
    public static function vratiIgru($igra_id){
        //TODO dodati proveru da li je igra povezana sa ulogovanim korisnikom
         $query = Igra::find()->select('*')->from('igra')->where('id = ' . $igra_id)->one();
        
        $provider = new ActiveDataProvider([
                'query' => $query
                ]);
            
         return $query;
    
    }
    
    public function stvoriIgruUBazi($idKorisnika){
        $this->setAttributes(['kreator_id' => $idKorisnika,  'aktivna' => 0
            , 'broj_igranja' => 0, 'sablon_igre_id' => 1]);
        return $this->save() ? $this : false;
    }
    
    public function vratiSopstveneIgre($idKorisnika){
        $query = self::find()->select('*')->from('igra')
                ->where('kreator_id = ' . $idKorisnika);
       
        $provider = new ActiveDataProvider([
                'query' => $query
                ]);
            
        return $provider->getModels();
    }
    
    public function  proveriDaLiSuSveIgreResene($korisnik_id){ //za sad nema funkciju u aplikaciji, moze biti korisno za kasnije
        $brojIgara = intval(self::find()->select('*')->where('kreator_id <> '. $korisnik_id)->count());
        $brojResenihIgara = (new ResenaIgra)->vratiBrojResenihIgara($korisnik_id);
        return $brojIgara >= $brojResenihIgara;
    }
    
    public static function vratiNeaktivneIgre($jedna = false){
    if($jedna){
        return self::findOne(['aktivna'=> 0]);
    }else{
        return self::findAll(['aktivna' => 0]);
        
    }
    }
}
