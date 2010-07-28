<?php
$this->breadcrumbs=array(
	'User in Group',
);

$this->menu=array(
	array('label'=>'Create User in Group', 'url'=>array('create')),
	array('label'=>'Manage Users in Group', 'url'=>array('admin')),
);
?>

<h1>Users in Groups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
