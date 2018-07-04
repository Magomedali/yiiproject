<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

?>

<h2>Справочник <span>"Тип упаковки"</span></h2>
<div class="status_create_page">
<?php $form = ActiveForm::begin(['id' => 'country_create']); ?>

    <?php echo $form->field($model, 'title')->textInput(array('class' => 'form-control')); ?>
    <div class="form-actions">
        <?php echo Html::submitButton('Сохранить',['class' => 'btn btn-primary', 'name' => 'country-create-button']); ?>
    </div>
    
<?php ActiveForm::end(); ?>
</div>