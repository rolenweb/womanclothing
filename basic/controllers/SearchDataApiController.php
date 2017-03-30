<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use app\models\SearchData;
use app\models\User;

class SearchDataApiController extends ActiveController
{
    public $modelClass = 'app\models\SearchData';

    public function beforeAction($action)
	{	
		$getData = Yii::$app->request->get();
		
		if (empty($getData['access-token'])) {
			return false;
		}
		$user = User::findIdentityByAccessToken($getData['access-token']);

		if (empty($user)) {
			return false;
		}

		
	    if (!parent::beforeAction($action)) {
	        return false;
	    }



	    // other custom code here

	    return true; // or false to not run the action
	}

    public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => QueryParamAuth::className(),
	    ];

	    return $behaviors;
	}

    public function actionIndex()
    {
    	return SearchData::find()->limit(2)->all();
    }

    public function create()
    {
    	
    }

    public function view($id)
    {
    	return SearchData::findOne($id);
    }

    public function checkAccess($action, $model = null, $params = [])
	{
		if (Yii::$app->user->isGuest) {
	    	return false;
	    }
	    return true;
	}

    
}
?>