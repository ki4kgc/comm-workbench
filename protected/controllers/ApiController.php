<?php

/**
 * @author: Matt Scaperoth
 * @date: 7-7-14
 * 
 * API for communications dashboard application.
 * This api uses the ApiHelper class to call various functions
 * This ApiHelper can be found in protected/components/. It extends CHTML.
 */
class ApiController extends Controller {
    
    //source and destination locations. A prefix of the application's base path
    //will be added before the action is exectured in beforeAction(). This base
    //path here is expected to be ../protected/components/
    private $source = '/data/backup/';
    private $dest = '/data/filesystem/';
    
    public function beforeAction($action)
    {
        $this->source = Yii::app()->basePath.$this->source;
        $this->dest = Yii::app()->basePath.$this->dest;
        return parent::beforeAction($action);
    }
    
    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    /**
     * Syncs /protected/data/filesystem contents with current filesystem in remote server
     * or vice versa
     */
    public function actionSync() {

        if ($push_or_pull = CHttpRequest::getParam('push_or_pull')) {
            $JSON_array = ApiHelper::_ProcessSync($push_or_pull, $this->source, $this->dest);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");
        
        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
        
    }

}