<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php 	echo $form->dropDownList($model, "userid", CHtml::listData(SmyUser::model()->OrderByNameAsc()->findAll(), "id", "name"),
															array("prompt"=>"Select a User:") );?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'reponame'); ?>
		<?php 				echo $form->dropDownList($model, 
										 "repositoryid", 
										 CHtml::listData(SmyRepositories::model()->OrderByNameAsc()->findAll(), "id", "name"), 
										 array("prompt"=>"Select a Repository:") ); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'access'); ?>
		<?php echo $form->dropDownList($model, "access", CHtml::listData(SmyUserPrivilege::retriveAccessList(), "id", "name"),
										array("prompt"=>"Select a Repository:") ); ?>>
	</div>

	<div class="row">
		<?php echo $form->label($model,'path'); ?>
		<?php echo $form->textField($model,'path',array('size'=>60)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->