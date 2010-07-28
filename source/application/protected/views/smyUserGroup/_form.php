<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'smy-user-group-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'groupid'); ?>
		<?php 
			echo $form->dropDownList($model, "groupid", CHtml::listData(SmyGroup::model()->OrderByNameAsc()->findAll(), "id", "name"),
																		array("prompt"=>"Select a Group:") );
		?>
		<?php echo $form->error($model,'groupid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userid'); ?>
		<?php 
			echo $form->dropDownList($model, "userid", CHtml::listData(SmyUser::model()->OrderByNameAsc()->findAll(), "id", "name"),
																array("prompt"=>"Select a User:") );
		?>
		<?php echo $form->error($model,'userid'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->