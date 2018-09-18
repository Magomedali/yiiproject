<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\rbac\models\AuthRule;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model budyaga\users\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <!-- <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?> -->

    <?= $form->field($model, 'rule_name')->dropDownList(ArrayHelper::map(AuthRule::find()->all(), 'name', 'name'), ['prompt' => Yii::t('rbac', 'SELECT...')]) ?>

    <?= Html::hiddenInput('AuthItem[type]', $type) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rbac', 'CREATE') : Yii::t('rbac', 'UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
