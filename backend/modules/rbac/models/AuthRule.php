<?php

namespace backend\modules\rbac\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cen_ts_auth_rule".
 *
 * @property string $name
 * @property string $data
 *
 * @property UserAuthItem[] $userAuthItems
 */
class AuthRule extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'checkRule'],
            [['data'], 'string'],
            ['data','default','value'=>''],
            [['name'], 'string', 'max' => 64]
        ];
    }

    public function checkRule($attribute, $params)
    {
        if (!class_exists($this->name)) {
            $this->addError('name', Yii::t('rbac', 'CLASS_DO_NOT_EXISTS', ['phpClass' => $this->name]));
        } else {
            $newRule = new $this->name;
            $existsRule = AuthRule::findOne(['name' => $newRule->name]);
            if ($existsRule) {
                $this->addError('name', Yii::t('rbac', 'RBAC_RULE_ALREADY_EXISTS', ['ruleName' => $newRule->name]));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('rbac', 'RBAC_NAME'),
            'data' => Yii::t('rbac', 'RBAC_DATA'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

}
