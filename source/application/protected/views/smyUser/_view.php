<?php $imgChecked = CHtml::image("images/Check-icon.png",'checked', array('style'=>'width:12px;height:12px;')); ?>
<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</b>
	<?php echo $data->isAdmin()? $imgChecked: CHtml::encode(" "); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repositorygrants')); ?>:</b>
	<?php echo $data->isRepositoryGrant()? $imgChecked: CHtml::encode(" "); ?>	
	<br />
</div>