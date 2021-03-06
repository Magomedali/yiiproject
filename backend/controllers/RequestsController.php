<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

use common\models\Request;
use backend\modules\RequestSearch;

use soapclient\methods\LoadCustomer;
use soapclient\methods\CreateReceipts;
use backend\models\Autotruck;
use backend\models\Client;
use common\models\ContractorExport;

/**
 * Requests controller
 */
class RequestsController extends Controller
{   

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','list','exec-all','exec-loadcustomer','exec-createreceipts','export-all-clients','error'],
                        'allow' => true,
                        'roles'=>['admin']
                    ],
                    
                ],
            ]
        ];
    }
    

    public function actionIndex(){
       

        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            $RequestSearch = new RequestSearch;
        
            $dataProvider = $RequestSearch->search(Yii::$app->request->get());

            $view = $this->renderPartial('monitoring',['dataProvider'=>$dataProvider,'RequestSearch'=>$RequestSearch]);

            return ['view'=>$view,'date'=>date("d.m.Y H:i:s",time()),'post'=>Yii::$app->request->queryParams];
        
        }else{

            $RequestSearch = new RequestSearch;
        
            $dataProvider = $RequestSearch->search(Yii::$app->request->get());

            $view = $this->renderPartial('monitoring',['dataProvider'=>$dataProvider,'RequestSearch'=>$RequestSearch]);

            return $this->render('index',['view'=>$view]);
        }
       
    }


    public function actionList(){


        return $this->render('list',[]);
    }


    public function actionExecAll(){
        return $this->render('result',[]);
    }



    public function actionExecUnloadRemnant(){
        return $this->render('result',[]);
    }


    public function actionExecLoadcustomer(){

        \common\modules\ExportUser::export(Yii::$app->user->identity);

        return $this->render('result',[]);
    }





    public function actionExecCreatereceipts(){


        $model = Autotruck::find()->limit(1)->orderBy(['date'=>SORT_DESC])->one();

        if(!isset($model->id))
            throw new \Exception("Документ не найден!",404);

        \common\modules\ExportAutotruck::export($model);
        return $this->redirect(['requests/index']);
    }

    public function actionExportAllClients(){

        set_time_limit(0);
        $models = Client::find()->all();
        foreach ($models as $key => $model) {
            \common\modules\ExportClient::export($model);
        }

        return $this->redirect(['requests/index']);
    }

}
