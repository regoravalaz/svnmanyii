<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'smy-group-privilege-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php /*echo $form->labelEx($model,'groupid'); 
		 echo $form->textField($model,'groupid'); 
		 echo $form->error($model,'groupid'); */?>
		
		<?php
		 	echo $form->labelEx($model,'groupname');
			if ($form->controller->action->id == "create")
			{
				echo $form->dropDownList($model, "groupid", CHtml::listData(SmyGroup::model()->OrderByNameAsc()->findAll(), "id", "name"),
															array("prompt"=>"Select a Group:") );
			}
			else
			{
				echo CHtml::textField("groupname", $model->groupname, array("readonly"=>"true", "class"=>"disabled")); 
			}		
		?>
		<?php echo $form->error($model,'groupid'); ?>	
	</div>

	<div class="row">
		<?php 
		/* echo $form->labelEx($model,'repositoryid'); 
		 echo $form->textField($model,'repositoryid');
		 echo $form->error($model,'repositoryid');*/ ?>
		 <?php
		  	echo $form->labelEx($model,'reponame');
			if ($form->controller->action->id == "create")
			{
				echo $form->dropDownList($model, 
										 "repositoryid", 
										 CHtml::listData(SmyRepositories::model()->OrderByNameAsc()->findAll(), "id", "name"), 
										 array("prompt"=>"Select a Repository:", "onchange"=>"Smy.GroupPrivileges.updateRepoNameFromList(this);" ) );
				echo CHtml::hiddenField("reponame", $model->reponame);
			}
			else
			{
				echo CHtml::textField("reponame", $model->reponame, array("readonly"=>"true", "class"=>"disabled")); 
			}
			echo $form->error($model,'repositoryid');
		 ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'access'); ?>
		<?php echo $form->dropDownList($model, "access", CHtml::listData(SmyGroupPrivilege::retriveAccessList(), "id", "name") ); ?>		
	</div>

	<div class="row" >
		<?php 
			echo $form->labelEx($model,'path');			
			$subfolders = Yii:: app()->repoHandler->retrieveSubfolders( $model->reponame, $model->path);
			$dataPath =  array( "form"=>$form, "parent" =>$model->path, "childs"=>$subfolders, "reponame"=> $model->reponame); 
		?>
		<div>
			<?php echo $this->renderPartial('_folderNav', $dataPath);?>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->