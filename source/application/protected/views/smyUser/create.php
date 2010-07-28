<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SmyUser', 'url'=>array('index')),
	array('label'=>'Manage SmyUser', 'url'=>array('admin')),
);
?>

<h1>Create User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'modelCurrentUser'=>$modelCurrentUser)); ?>