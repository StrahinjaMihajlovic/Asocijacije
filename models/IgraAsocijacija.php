<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "igra_asocijacija".
 *
 * @property string $igra_id
 * @property string $asocijacija_id
 *
 * @property Asocijacija $asocijacija
 * @property Igra $igra
 */
class IgraAsocijacija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'igra_asocijacija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['igra_id', 'asocijacija_id'], 'required'],
            [['igra_id', 'asocijacija_id'], 'integer'],
            [['igra_id', 'asocijacija_id'], 'unique', 'targetAttribute' => ['igra_id', 'asocijacija_id']],
            [['asocijacija_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asocijacija::className(), 'targetAttribute' => ['asocijacija_id' => 'id']],
            [['igra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Igra::className(), 'targetAttribute' => ['igra_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'igra_id' => 'Igra ID',
            'asocijacija_id' => 'Asocijacija ID',
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
    public function getIgra()
    {
        return $this->hasOne(Igra::className(), ['id' => 'igra_id']);
    }
}
