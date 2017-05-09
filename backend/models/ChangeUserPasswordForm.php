<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;


/**
 * Login form
 */
class ChangeUserPasswordForm extends Model
{
    public $new_password;
    public $confirm_password;
    public $user_id;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [[ 'new_password','confirm_password'], 'required'],
            // password is validated by validatePassword()
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findUserByID($this->user_id);
        }

        return $this->_user;
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {

        $user = $this->getUser();
        $user->setPassword($this->new_password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}

