<?php
$this->breadcrumbs=array(
	'Group Privileges'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Group Privileges', 'url'=>array('index')),
	array('label'=>'Manage Group Privileges', 'url'=>array('admin')),
);
?>

<h1>Create Group Privilege</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>