<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$class = "Transfer[{$n}]";
?>

<tr>
	<td>-</td>
	<td>
		<?php echo Html::dropDownList($class."[client_id]",null,ArrayHelper::map($clients,'id','name'),['prompt'=>'Выберите клиента','class'=>'form-control']);?>
	</td>
	<td>
		<?php echo Html::textInput($class."[name]",null,['class'=>'form-control']);?>
	</td>
	<td>
		<?php echo Html::textInput($class."[sum]",null,['class'=>'sum form-control']);?>
	</td>
	<td>
		<?php echo Html::textInput($class."[sum_ru]",null,['class'=>'sum_ru form-control','readonly'=>1]);?>
	</td>
	<td>
		<?php echo Html::textInput($class."[comment]",null,['class'=>'form-control']);?>
	</td>
	<td>
		<?php echo Html::a("X",null,['class'=>'btn btn-danger removeRow','data-confirm'=>'Подтвердите свои дейсвтия']);?>
	</td>
</tr>