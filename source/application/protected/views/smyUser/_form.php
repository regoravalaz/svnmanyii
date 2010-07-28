<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'smy-user-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->hiddenField($model,'oldname',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword',array('size'=>32,'maxlength'=>32, 'value'=>'')); ?>
		<?php echo $form->error($model,'newPassword'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'retypePassword'); ?>
		<?php echo $form->passwordField($model,'retypePassword',array('size'=>32,'maxlength'=>32, 'value'=>'')); ?>
		<?php echo $form->error($model,'retypePassword'); ?>
	</div>	

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php 
			echo $form->checkBox($model,'admin',$model->isAdmin()?array("value"=>SmyUser::Admin, "checked"=>"checked")
																 :array("value"=>SmyUser::Admin)); 
		?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'repositorygrants'); ?>
		<?php 
			$parameterGrant = $model->isAdmin() ?array("value"=>$model["repositorygrants"], "value"=>$model["repositorygrants"])
										 		:array("value"=>$model["repositorygrants"]);
			echo $form->checkBox($model,'repositorygrants'); 
		?>
		<?php echo $form->error($model,'repositorygrants'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'autorizePassword'); ?>
		<?php echo $form->passwordField($model,'autorizePassword',array('size'=>32,'maxlength'=>32, 'value'=>'')); ?>
		<?php echo $form->error($model,'autorizePassword'); ?>
	</div>
		
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->