<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Currency;

$class = "SellerExpenses[{$n}]";
?>

<tr>
	<td>-</td>
	<td>
		<?php echo Html::input("date",$class."[date]",null,['class'=>'form-control']);?>
	</td>
	<td>
		<?php echo Html::dropDownList($class."[seller_id]",null,ArrayHelper::map($sellers,'id','name'),['prompt'=>'Выберите поставщика','class'=>'form-control']);?>
	</td>
	<td>
		<?php
			echo Html::dropDownList($class."[currency]",null,ArrayHelper::map(Currency::getCurrencies(),'id','title'),['prompt'=>'Выберите валюту','class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::textInput($class."[course]",null,['class'=>'form-control compute_sum']);
		?>
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
		<?php echo Html::a("<i class=\"material-icons\">close</i>",null,['class'=>'btn btn-danger btn-sm btn-round removeRow','data-confirm'=>'Подтвердите свои дейсвтия']);?>
	</td>
</tr>