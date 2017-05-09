<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class JobSiteForm extends Model {
    public $city;
    public $state;
    public $check_in_date;
    public $check_out_date;
    public $single_rooms;
    public $double_rooms;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city', 'state', 'check_in_date', 'check_out_date', 'single_rooms', 'double_rooms'], 'required'],
        ];
    }

}

