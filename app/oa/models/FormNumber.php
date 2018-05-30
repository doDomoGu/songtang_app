<?php

namespace oa\models;

use ucenter\models\District;
use ucenter\models\Industry;
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

    public static function generate($form_id){
        $form_number = '';
        if(in_array($form_id,[12,13])){
            $user = Yii::$app->user->getIdentity();
            $year = date('Y');
            $month = date('m');
            $district_id = $user->district_id;
            $industry_id = $user->industry_id;
            $one = self::find()->where(['form_id'=>$form_id,'year'=>$year,'month'=>$month,'district_id'=>$district_id,'industry_id'=>$industry_id])->one();
            if($one){
                $number = $one->current_number;
                $one->current_number = (string)($number + 1);
                $one->save();
            }else{
                $number = 1;
                $new = new self();
                $new->form_id = $form_id;
                $new->year = $year;
                $new->month = $month;
                $new->district_id = $district_id;
                $new->industry_id = $industry_id;
                $new->current_number = '2';
                $new->save();
            }

            
            $number = $number<10 ? '00'.$number : ($number < 100 ? '0'.$number : $number);
            $district_alias = strtoupper(District::getAlias($district_id));
            $industry_alias = strtoupper(Industry::getAlias($industry_id));

            $form_number = $district_alias.$industry_alias.$year.$month.$number;

        }
        return $form_number;
    }
}
