<?php

namespace frontend\modules;

use common\models\Sender;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use frontend\modules\FilterModelBase;
use common\models\PaymentState;
use frontend\models\CustomerPayment;
use yii\db\Query;

class SendersSearch extends Sender
{
    /**
     * Принимаемые моделью входящие данные
     */
    public $page_size = 100;


    /**
     * Правила валидации модели
     * @return array
     */
    public function rules()
    {
        return [
            [['name','phone','email'],'safe']
        ];
    }

    public function scenarios(){
        return Sender::scenarios();
    }


    /**
     * Реализация логики выборки
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function search($params)
    {   

        $query = Sender::find();
        $query->orderBy(['name' => SORT_ASC]);
        /**
             * Создаём DataProvider, указываем ему запрос, настраиваем пагинацию
             */
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => new Pagination([
                    'pageSize' => $this->page_size
                ])
            ]);

        //если данные не фильтра не переданы или переданы не валидные данныеы
        if(!($this->load($params) && $this->validate())){

            return $dataProvider;
        }


        if($this->name){
            $query->andWhere("name LIKE '{$this->name}%'");
        }

       

        if($this->phone){
            $query->andWhere("phone LIKE '{$this->phone}%'");
        }
        
        if($this->email){
            $query->andWhere("email LIKE '{$this->email}%'");
        }
        

        

        
        
        return $dataProvider;
    }

}