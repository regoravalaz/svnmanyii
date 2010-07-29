<?php
$this->breadcrumbs=array(
	'User Privileges',
);

$this->menu=array(
	array('label'=>'Create User Privilege', 'url'=>array('create')),
	array('label'=>'Manage User Privileges', 'url'=>array('admin')),
);
?>

<h1>User Privileges</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
