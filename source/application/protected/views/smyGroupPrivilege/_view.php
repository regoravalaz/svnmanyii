<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupname')); ?>:</b>
	<?php echo CHtml::encode($data->groupname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reponame')); ?>:</b>
	<?php echo CHtml::encode($data->reponame); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('accestype')); ?>:</b>
	<?php echo CHtml::encode($data->accestype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('path')); ?>:</b>
	<?php echo CHtml::encode($data->path); ?>
	<br />


</div>