<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ChangePasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $confirm_password;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['old_password', 'new_password','confirm_password'], 'required'],
            // password is validated by validatePassword()
            ['old_password','validatePassword'],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user || !$user->validatePassword($this->old_password)) {
            $this->addError($attribute, 'Incorrect password.');
        }
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findIdentity(\Yii::$app->user->identity->id);
        }

        return $this->_user;
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword($token = '')
    {
        if($token){
            $user=$token;
        }else{
            $user = $this->_user;
        }
        $user->setPassword($this->new_password);
        $user->removePasswordResetToken();
        $user->password_changed = true;

        return $user->save(false);
    }
}

