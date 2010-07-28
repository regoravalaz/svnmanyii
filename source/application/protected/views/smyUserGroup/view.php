<?php
$this->breadcrumbs=array(
	'User in Groups'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Users in Groups', 'url'=>array('index')),
	array('label'=>'Create User in Group', 'url'=>array('create')),
	array('label'=>'Update User in Group', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User in Group', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Users in Group', 'url'=>array('admin')),
);
?>

<h1>View Users in Group #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'groupname',
	),
)); ?>
