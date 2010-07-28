<?php
$this->breadcrumbs=array(
	'User Privileges'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List User Privilege', 'url'=>array('index')),
	array('label'=>'Create User Privilege', 'url'=>array('create')),
	array('label'=>'View User Privilege', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage User Privilege', 'url'=>array('admin')),
);
?>

<h1>Update User Privilege #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'subfolders'=>$subfolders)); ?>