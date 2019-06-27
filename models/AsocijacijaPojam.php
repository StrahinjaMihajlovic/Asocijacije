<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asocijacija_pojam".
 *
 * @property string $asocijacija_id
 * @property string $pojam_id
 *
 * @property Asocijacija $asocijacija
 * @property Pojam $pojam
 */
class AsocijacijaPojam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asocijacija_pojam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asocijacija_id', 'pojam_id'], 'required'],
            [['asocijacija_id', 'pojam_id'], 'integer'],
            [['asocijacija_id', 'pojam_id'], 'unique', 'targetAttribute' => ['asocijacija_id', 'pojam_id']],
            [['asocijacija_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asocijacija::className(), 'targetAttribute' => ['asocijacija_id' => 'id']],
            [['pojam_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pojam::className(), 'targetAttribute' => ['pojam_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asocijacija_id' => 'Asocijacija ID',
            'pojam_id' => 'Pojam ID',
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
    public function getPojam()
    {
        return $this->hasOne(Pojam::className(), ['id' => 'pojam_id']);
    }
}
