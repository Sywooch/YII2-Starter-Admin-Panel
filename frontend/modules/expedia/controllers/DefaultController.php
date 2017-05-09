<?php

namespace frontend\modules\expedia\controllers;

use frontend\components\RestController;
/**
 * Default controller for the `expedia` module
 */
class DefaultController extends RestController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->sendResponse(200,json_encode($this->expediaConfig()));
    }
}
