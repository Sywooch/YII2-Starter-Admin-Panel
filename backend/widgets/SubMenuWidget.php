<?php
namespace backend\widgets;

use yii\base\Widget;

class SubMenuWidget extends Widget{

    public $controller;
    public $menus;

    public function run(){
        return $this->render('subMenuWidget',['controller'=>$this->controller,'menus'=>$this->menus]);
    }
}
?>
