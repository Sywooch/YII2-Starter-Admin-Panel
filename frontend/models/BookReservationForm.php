<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class BookReservationForm extends Model {
    public $first_name;
    public $last_name;
    public $email;
    public $home_phone;
    public $work_phone;
    public $credit_card_type;
    public $credit_card_type_disp;
    public $credit_card_number;
    public $credit_card_identifier;
    public $credit_card_exp_month;
    public $credit_card_exp_year;
    public $address;
    public $city;
    public $country;
    public $postal_code;
    public $state;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'city',
                'state',
                'first_name',
                'last_name',
                'email',
                'home_phone',
                'work_phone',
                'credit_card_type',
                'credit_card_number',
                'credit_card_identifier',
                'credit_card_exp_month',
                'credit_card_exp_year',
                'address',
                'city',
                'country',
                'postal_code',
                'state',

            ], 'required'],
            ['email','email']
        ];
    }

}

