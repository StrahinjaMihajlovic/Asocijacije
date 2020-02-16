<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "neprimerene_reci".
 *
 * @property int $id
 * @property string $rec
 */
class NeprimereneReci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'neprimerene_reci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rec'], 'required'],
            [['rec'], 'string', 'max' => 30],
            [['rec'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rec' => 'Rec',
        ];
    }
}
