<?php 

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use frontend\models\App;
use frontend\models\Autotruck;
use frontend\models\ExpensesManager;
use common\models\User;
use yii\helpers\Url;
use common\models\TypePackaging;

$roleexpenses = 'autotruck/addexpenses';

$expensesManager = new ExpensesManager;

$AutotruckExpenses =ExpensesManager::getAutotruckExpenses($autotruck->id);

$Autotrucks = Autotruck::find()->orderBy('id')->all();

$this->title = $autotruck->name;

$packages = TypePackaging::find()->all();
?>



<div class="base_content">
	<div class="row">
		<div class="col-xs-12">
			<h1>Заявка: <?=$autotruck->name?></h1>
			 
		</div>
	</div>
	<?php if(Yii::$app->session->hasFlash('ExpensesManagerAddSuccess')): ?>
		<div class="alert alert-success">
   			Расход записан.
		</div>
	<?php endif; ?>

	<?php if(Yii::$app->session->hasFlash('ExpensesManagerAddError')): ?>
		<div class="alert alert-error">
   			Не удалось записать расход.
		</div>
	<?php endif; ?>

	<?php if(Yii::$app->session->hasFlash('AutotruckSaved')): ?>
	<div class="alert alert-success">
    	Заявка сохранена!
	</div>
	<?php endif; ?>
	<?php if(Yii::$app->session->hasFlash('FileUploadError')): ?>
		<div class="alert alert-danger">
    		Файл не загружен, произошла ошибка при валидации.
		</div>
	<?php endif; ?>


	<?php if($autotruck){?>
		<div class="app_blocks">
				<div id="autotruck_tab_<?=$autotruck->id?>" class="">
				  	<div class="panel panel-primary">
				  		<div class="panel-heading">
				  			<div class="row">
				  				<div class="col-xs-6">
				  					<?=$autotruck->name?> №<?=$autotruck->id?>
				  				</div>
				  				<div class="col-xs-6" style="text-align:right;padding-right:20px;">
				  					<span>Дата: <?=date("d.m.Y",strtotime($autotruck->date))?></span>
				  				</div>
				  			</div>
				  		</div>

						<div class="panel-body autotruck_info" style="padding:5px;">
							<div class="row">
								<div class="col-xs-3">
									<p>Страна поставки: <span><?php echo $autotruck->supplierCountry->country?></span></p>
								</div>
								<div class="col-xs-3">
									<p>Курс: <span><?php echo $autotruck->course?> руб.</span></p>
								</div>
								<div class="col-xs-3 col-xs-offset-3">
									
								</div>
								
							</div>
							<div class="row" style="margin-top:20px;">
								<div class="col-xs-3">
									<p>Статус:</p>
									<ul>
										<?php
											$autotruck->activeStatus->title;
											$story = $autotruck->traceStory;
											if(is_array($story)){
												foreach ($story as $key => $s) { 
													$active_s = ($s->status_id == $autotruck->status)? "active_status" :'';
										?>
													
											<li class="app_status <?=$active_s?>">
												<?=$s->status->title?>
												<span><?=date('d.m.Y',strtotime($s->trace_date))?></span>
											</li>
										<?php	} } ?>
									</ul>
								</div>
								<div class="col-xs-3">
									<div>
										<p>Описание:</p>
										<?=$autotruck->description?>
									</div>
								</div>
								<div class="col-xs-3">
									<div>
										<p>Прикрепленный файл:</p>
										<?php 

											if($autotruck->file){
												$files = explode('|', $autotruck->file);
												foreach ($files as $key => $file) {
													if($file && file_exists('uploads/'.$file)){
														
														echo Html::a($file,['autotruck/download','id'=>$autotruck->id,'file'=>$file],['target'=>'_blank']),'   ',
															Html::a("x",['autotruck/unlinkfile','id'=>$autotruck->id,'file'=>$file],['data-confirm'=>'Подтвердите удаление файла']),
														"<br>";
														
													}
												}
											} 
												?>
									</div>
									<div>
										<p>Итого кол-во мест: <?php echo $autotruck->appCountPlace?></p>
										<?php
											if(is_array($packages)){
												foreach ($packages as $key => $package) {
													$count = $autotruck->getAppCountPlacePackage($package->id);

													if($count > 0){
														?>
														<p><?php echo $package->title?>: <?php echo $count; ?></p>
														<?php
													}
												}
											}
										?>
									</div>
								</div>
								<div class="col-xs-3">
									<p>Номер машины: <?php echo Html::encode($autotruck->auto_number)?></p>
									<p>Транспорт: <?php echo Html::encode($autotruck->auto_name)?></p>
									<p>ГТД: <?php echo Html::encode($autotruck->gtd)?></p>
									<p>Оформление: <?php echo Html::encode($autotruck->decor)?></p>
								</div>
							</div>
							<div class="app_update_btn">
									<?php echo Html::a('Редактировать', array('autotruck/update','id'=>$autotruck->id), array('class' => 'btn btn-default')); ?>

									<?php echo Html::a('Выгрузить в excel', array('autotruck/to-excel','id'=>$autotruck->id), array('class' => 'btn btn-success')); ?>
							</div>
							<ul class="nav nav-tabs">
  								<li class="active"><a data-toggle="tab" href="#apps">Наименования</a></li>

								<?php if(Yii::$app->user->can($roleexpenses)){?>
  									<li><a data-toggle="tab" href="#expenses">Расходы</a></li>
  								<?php } ?>
							</ul>
							<div class="tab-content">
								<div id="apps" class="tab-pane fade in active">
										<div class="table autotruck_apps">
											<table class="table table-striped table-hover table-bordered">
											<tbody>
												<tr>
													<th>№   
														<?php 
															$autotruckApps = $autotruck->getApps();
															$c = ($autotruck->getCountOutStockApp() == count($autotruckApps)) ? 1 : 0;

															echo Html::checkbox('out_stock_all',$c,['id'=>'out_stock_all','value'=>$autotruck->id]);
														?>
													</th>
													<th class="app_client">Клиент</th>
													<th class="app_sender">Отправитель</th>
													<th class="app_info">Наименование</th>
													<th class="app_place">Кол-во мест</th>
													<th class="app_package">Упаковка</th>
													<th>Вес (кг)</th>
													<th>Ставка ($)</th>
													<th>Сумма ($)</th>
													<th>Сумма (руб)</th>
													<th>Комментарий</th>
												</tr>
										<?php $cweight=0; $total = 0; $total_us = 0;
										foreach ($autotruckApps as $key => $app) { ?>
												<tr>
													<td>
														<?php echo $key+1?>
														<?php 
															echo Html::checkbox('out_stock[]',$app->out_stock,['value'=>$app->id,'class'=>'out_stock_item']);
														?>
													</td>
													<td><?php echo Html::a($app->buyer->name,['client/read','id'=>$app->client],array('target'=>'_blank'));?></td>
													
													<?php 
														if(!$app->type){
														?>
															<td>
																<?php echo $app->sender 
																			? $app->senderObject->name 
																			: "Не указан"; 
																?>
															</td>
														    <td><?=$app->info?></td>
															<td><? echo $app->count_place ?></td>
														
															<td><?php echo $app->package ? $app->typePackaging->title : "Не указан"; ?></td>
														<?php	
														}else{
															?>
															<td></td>
															<td><?=$app->info?></td>
															<td colspan="2"></td>
															<?php
														}
													?>
													
													
													
													<td><? echo $app->type ? '': $app->weight?></td>
													<td><?=$app->rate?></td>
													<td><? echo $app->summa_us; ?> $</td>
													<td>


													<? //echo $app->type ? round($app->rate*$autotruck->course,2) : round($app->weight*$app->rate*$autotruck->course,2)
														$rate_vl = $app->weight > 0 ? $app->summa_us/$app->weight : 0;
														$sum_ru = $app->weight * $rate_vl * $autotruck->course;

														echo $app->type ? round($app->rate*$autotruck->course,2) : round($sum_ru,2);
													?> 

													руб</td>
													
													<td><?=$app->comment?></td>
												</tr>
										<?php 
											$cweight += $app->type ? 0 : $app->weight; 

											$total+= $app->type? round($app->rate*$autotruck->course,2) : round($app->summa_us*$autotruck->course,2);

											$total_us+=$app->summa_us;
										 }?>
										<tr>
												<td colspan="4"><strong>Итого</strong></td>
												<td colspan="2"><strong><?php echo $autotruck->appCountPlace?></strong></td>
												<td><strong><?php echo round($cweight,2);?> кг.</strong></td>
												<td></td>
												<td><strong><?php echo round($total_us,2);?> $</strong></td>
												<td><strong><?php echo round( $total,2);?> руб.</strong></td>
												<td></td>
											</tr>
											</tbody>
											</table>
										</div>
								</div> <!-- Контент наименования -->

								<?php
									$script = <<<JS

										$(".out_stock_item").change(function(event){
											if($(this).prop("checked")){

												var tItem = $(this);
												$.ajax({
													url:'index.php?r=autotruck/set-out-stock&id='+parseInt($(this).val())+'&value=1',
													method: 'GET',
													dataType:'json',
													success:function(json){
														if(parseInt(json['result'])){
															tItem.prop("checked",1);
														}else{
															tItem.prop("checked",0);
														}
													}
												})
											}else{
												var tItem = $(this);
												$.ajax({
													url:'index.php?r=autotruck/set-out-stock&id='+parseInt($(this).val())+'&value=0',
													method: 'GET',
													dataType:'json',
													success:function(json){
														if(parseInt(json['result'])){
															tItem.prop("checked",0);
														}else{
															tItem.prop("checked",1);
														}
													}
												})
											}



											event.preventDefault();
										});


										$("#out_stock_all").change(function(event){

											if($(this).prop("checked")){

												var tItem = $(this);
												$.ajax({
													url:'index.php?r=autotruck/set-all-out-stock&id='+parseInt($(this).val())+'&value=1',
													method: 'GET',
													dataType:'json',
													success:function(json){
														if(parseInt(json['result'])){
															tItem.prop("checked",1);
															$(".out_stock_item").prop("checked",1);
														}else{
															tItem.prop("checked",0);
															$(".out_stock_item").prop("checked",0);
														}
													}
												})
											}else{
												var tItem = $(this);
												$.ajax({
													url:'index.php?r=autotruck/set-all-out-stock&id='+parseInt($(this).val())+'&value=0',
													method: 'GET',
													dataType:'json',
													success:function(json){
														if(parseInt(json['result'])){
															tItem.prop("checked",0);
															$(".out_stock_item").prop("checked",0);
														}else{
															tItem.prop("checked",1);
															$(".out_stock_item").prop("checked",1);
														}
													}
												})
											}

											event.preventDefault();
										})
JS;
									$this->registerJs($script);
								?>


								<?php if(Yii::$app->user->can($roleexpenses)){?>
								<div id="expenses" class="tab-pane fade in">
									<div class="row">
										<div class="col-xs-12">
											<?php 
											if(count($AutotruckExpenses)){
												$dataProvider = new ActiveDataProvider([
	           										'query' => ExpensesManager::find()->where(['autotruck_id'=>$autotruck->id]),
	            								]);
	            								echo GridView::widget([
	            										'dataProvider' => $dataProvider,
	            										'summary'=>"",
	            										'columns'=>[
	            											['class'=>'yii\grid\SerialColumn'],
	            											[
	            												'attribute'=>'date',
	            												'value'=>function($e){
	            													return date("d.m.Y",strtotime($e->date));
	            												}
	            											],
	            											[
	            												'attribute'=>'manager_id',
	            												'value'=>'manager.name'
	            											],
	            											'cost',
	            											'comment'
	            										]
	            									]);
											} ?>
										</div>
									</div>
								</div>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
		</div>
	<?php } ?>
</div>
