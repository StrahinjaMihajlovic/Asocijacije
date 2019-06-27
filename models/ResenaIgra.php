<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resena_igra".
 *
 * @property string $igra_id
 * @property int $korisnik_id
 *
 * @property Igra $igra
 * @property Korisnik $korisnik
 */
class ResenaIgra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resena_igra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['igra_id', 'korisnik_id'], 'required'],
            [['igra_id', 'korisnik_id'], 'integer'],
            [['igra_id', 'korisnik_id'], 'unique', 'targetAttribute' => ['igra_id', 'korisnik_id']],
            [['igra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Igra::className(), 'targetAttribute' => ['igra_id' => 'id']],
            [['korisnik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Korisnik::className(), 'targetAttribute' => ['korisnik_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'igra_id' => 'Igra ID',
            'korisnik_id' => 'Korisnik ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgra()
    {
        return $this->hasOne(Igra::className(), ['id' => 'igra_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKorisnik()
    {
        return $this->hasOne(Korisnik::className(), ['id' => 'korisnik_id']);
    }
}
