<?php
namespace backend\widgets;

use yii\base\Widget;

class MenuWidget extends Widget{

    public $menuArr;

    public function run(){
        return $this->render('menuWidget',['menuArr'=>$this->menuArr]);
    }
}
?>