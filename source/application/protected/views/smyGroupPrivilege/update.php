<?php
$this->breadcrumbs=array(
	'Group Privileges'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Group Privileges', 'url'=>array('index')),
	array('label'=>'Create Group Privilege', 'url'=>array('create')),
	array('label'=>'View Group Privileges', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Group Privileges', 'url'=>array('admin')),
);
?>

<h1>Update Group Privilege #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>