<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kategorija".
 *
 * @property string $id
 * @property string $naziv
 * @property string $roditelj_id
 * @property int $levo
 * @property int $desno
 *
 * @property Igra[] $igras
 * @property Kategorija $roditelj
 * @property Kategorija[] $kategorijas
 */
class Kategorija extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategorija';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['naziv'], 'required'],
            [['roditelj_id', 'levo', 'desno'], 'integer'],
            [['naziv'], 'string', 'max' => 50],
            [['roditelj_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kategorija::className(), 'targetAttribute' => ['roditelj_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'naziv' => 'Naziv',
            'roditelj_id' => 'Roditelj ID',
            'levo' => 'Levo',
            'desno' => 'Desno',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIgras()
    {
        return $this->hasMany(Igra::className(), ['kategorija_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoditelj()
    {
        return $this->hasOne(Kategorija::className(), ['id' => 'roditelj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategorijas()
    {
        return $this->hasMany(Kategorija::className(), ['roditelj_id' => 'id']);
    }
}
