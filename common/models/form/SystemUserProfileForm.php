<?php
namespace common\models\form;
use Yii;
use yii\base\Model;
use common\models\User;

class SystemUserProfileForm extends Model
{
    public $id;
    public $user_id;
    public $phone_no;
    public $email;
    public $first_name;
    public $last_name;
    public $gender;
    public $date_of_birth;
    public $media_id;
    public $role;

 	public function rules()
    {
        return [
            // all are required
            [['first_name', 'last_name','phone_no','email','gender','role'], 'required'],
            [['email'],'email'],
            [['media_id', 'gender'], 'integer'],            
            ['email','custom_user_unique'],
            [['date_of_birth','gender','phone_no',],'safe']
        ];
    }
    public function custom_user_unique($attribute, $params)
    {
        $check =false;
        if($this->user_id){
            $is_change = User::find()->where(['id'=>$this->user_id])->andWhere(['email'=>$this->email])->one();
            if(!$is_change){
                $check = User::findOne(['email'=>$this->$attribute]);
            }
        }else{
            $check = User::findOne(['email'=>$this->$attribute]);
        }
        if($check)
            $this->addError($attribute, 'This email address has already been taken.');
    }
}