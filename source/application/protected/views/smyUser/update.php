<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

	if( Yii::app()->user->checkAccess('admin') )
	{
		$this->menu=array(
			array('label'=>'List Users', 'url'=>array('index')),
			array('label'=>'Create User', 'url'=>array('create')),
			array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
			array('label'=>'Manage Users', 'url'=>array('admin')),
		);
	}
	else 
	{
		$this->menu=array(
			array('label'=>'View My Profile', 'url'=>array('view', 'id'=>$model->id)),
		);
	}
?>
<h1>Updating User: #<?php echo $model->id ?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model,'modelCurrentUser'=>$modelCurrentUser)); 