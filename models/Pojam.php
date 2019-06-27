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
}
