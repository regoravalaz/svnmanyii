<?php
$this->breadcrumbs=array(
	'Group Privileges'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Group Privilege', 'url'=>array('index')),
	array('label'=>'Create Group Privilege', 'url'=>array('create')),
	array('label'=>'Update Group Privilege', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Group Privilege', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Group Privilege', 'url'=>array('admin')),
);
?>

<h1>View Group Privilege #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'groupname',
		'reponame',
		'accestype',
		'path',
	),
)); ?>
