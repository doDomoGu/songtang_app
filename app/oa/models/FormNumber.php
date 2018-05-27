<?php

namespace app\oa\models;

use Yii;

/**
 * This is the model class for table "form_number".
 *
 * @property int $id
 * @property int $form_id
 * @property string $year
 * @property string $month
 * @property int $district_id
 * @property int $industry_id
 * @property string $current_number
 */
class FormNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_number';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_oa');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id', 'year', 'month', 'district_id', 'industry_id', 'current_number'], 'required'],
            [['form_id', 'district_id', 'industry_id'], 'integer'],
            [['year', 'month', 'current_number'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_id' => 'Form ID',
            'year' => 'Year',
            'month' => 'Month',
            'district_id' => 'District ID',
            'industry_id' => 'Industry ID',
            'current_number' => 'Current Number',
        ];
    }
}
