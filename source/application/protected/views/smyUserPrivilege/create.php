<?php
$this->breadcrumbs=array(
	'User Privileges'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List User Privileges', 'url'=>array('index')),
	array('label'=>'Manage User Privileges', 'url'=>array('admin')),
);
?>

<h1>Create User Privilege</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>