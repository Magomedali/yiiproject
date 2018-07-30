<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use frontend\models\Autotruck;
use common\models\User;
use common\models\Organisation;
use common\base\ActiveRecordVersionable;
use yii\db\Query;

/**
*
*
*
*/

class ExpensesManager extends ActiveRecordVersionable
{


	public function rules(){
		return [
            // name, email, subject and body are required
            [['manager_id','cost','autotruck_id','date'], 'required'],
            ['cost','double'],
            ['organisation','default','value'=>0]
        ];
	}

    
    public static function versionableAttributes(){
        return [
            'manager_id',
            'autotruck_id',
            'cost',
            'comment',
            'date',
            'organisation',
            'isDeleted'
        ];
    }


	/**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Comments the static model class
     */
	public static function model($className = __CLASS__){

		return parent::model($className);
	
	}

	/**
     * @return string the associated database table name
     */

	public static function tableName(){
		return '{{%expenses_manager}}';
	}

	/**
     * @return array primary key of the table
     **/     
    public static function primaryKey(){
    	return array('id');
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels(){
    	return array(
    		'id'=>'Номер',
    		'autotruck_id'=>'Заявка',
            'manager_id'=>'Менеджер',
            'cost'=>'Сумма ($)',
            'comment'=>'Комментарии',
            'organisation'=>'Организация',
            'date'=>'Дата'
    		);
    }


    public function getAutotruck(){
        return $this->hasOne(Autotruck::className(),["id"=>'autotruck_id']);
    }

    public function getManager(){
        return $this->hasOne(User::className(),["id"=>'manager_id']);
    }

    public function getOrg(){
        return $this->hasOne(Organisation::className(),["id"=>'organisation']);
    }

    public static function getAutotruckExpenses($autotruck_id){
        return ExpensesManager::find()->where(['autotruck_id'=>$autotruck_id])->all();
    }



    public function getHistory(){
        if(!$this->id) return false;

        return (new Query)
                ->select([
                    'rs.*',
                    'u.name as creator_name',
                    'u.username as creator_username',
                    'o.org_name',
                    'u2.name as manager_name'
                ])
                ->from(['rs'=>self::resourceTableName()])
                ->leftJoin(['u'=>User::tableName()]," rs.creator_id = u.id")
                ->leftJoin(['o'=>Organisation::tableName()]," o.id = rs.organisation")
                ->leftJoin(['u2'=>User::tableName()]," u2.id = rs.manager_id")
                ->where([static::resourceKey()=>$this->id])
                ->orderBy(["rs.id"=>SORT_DESC])
                ->all();
    }

}