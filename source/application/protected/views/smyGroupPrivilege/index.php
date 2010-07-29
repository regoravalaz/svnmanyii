<?php
$this->breadcrumbs=array(
	'Group Privileges',
);

$this->menu=array(
	array('label'=>'Create Group Privilege', 'url'=>array('create')),
	array('label'=>'Manage Group Privileges', 'url'=>array('admin')),
);
?>

<h1>Group Privileges</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
