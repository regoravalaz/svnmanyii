<?php
$this->breadcrumbs=array(
	'User Privilege'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List User Privileges', 'url'=>array('index')),
	array('label'=>'Add User Privilege', 'url'=>array('create')),
	array('label'=>'Update User Privilege', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User Privilege', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User Privileges', 'url'=>array('admin')),
);
?>

<h1>View User Privilege #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'reponame',
		'accestype',
		'path',
	),
)); 
?>

