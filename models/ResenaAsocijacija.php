<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resena_asocijacija".
 *
 * @property string $asocijacija_id
 * @property int $korisnik_id
 *
 * @property Asocijacija $asocijacija
 * @property Korisnik $korisnik
 */
class ResenaAsocijacija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resena_asocijacija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asocijacija_id', 'korisnik_id'], 'required'],
            [['asocijacija_id', 'korisnik_id'], 'integer'],
            [['asocijacija_id', 'korisnik_id'], 'unique', 'targetAttribute' => ['asocijacija_id', 'korisnik_id']],
            [['asocijacija_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asocijacija::className(), 'targetAttribute' => ['asocijacija_id' => 'id']],
            [['korisnik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['korisnik_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asocijacija_id' => 'Asocijacija ID',
            'korisnik_id' => 'Korisnik ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsocijacija()
    {
        return $this->hasOne(Asocijacija::className(), ['id' => 'asocijacija_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKorisnik()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'korisnik_id']);
    }
}
