<?php
$this->breadcrumbs=array(
	'Users in Group'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Users in Group', 'url'=>array('index')),
	array('label'=>'Manage Users in Group', 'url'=>array('admin')),
);
?>

<h1>Create User in Group</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>