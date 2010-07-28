<?php
$this->breadcrumbs=array(
	'Smy User Groups'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SmyUserGroup', 'url'=>array('index')),
	array('label'=>'Create SmyUserGroup', 'url'=>array('create')),
	array('label'=>'View SmyUserGroup', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SmyUserGroup', 'url'=>array('admin')),
);
?>

<h1>Update User in Group #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>