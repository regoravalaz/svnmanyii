<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name,
);



	if( Yii::app()->user->checkAccess('admin') )
	{
		$this->menu=array(
			array('label'=>'List', 'url'=>array('index')),
			array('label'=>'Create', 'url'=>array('create')),
			array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
			array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
			array('label'=>'Manage', 'url'=>array('admin')),
		);
	}
	else 
	{
		$this->menu=array(
			array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
		);
	}
?>

<h1>User Selected: #<?php echo $model->id ?></h1>

<?php 
	$imgChecked = CHtml::image("images/Check-icon.png",'checked', array('style'=>'width:12px;height:12px;'));
	$attributes = array('name',
						'email');
	
	$attributes[] = array(  'label'=>'Admin', 
							'type' =>'raw', 'value'=> $model->isAdmin()? $imgChecked: CHtml::encode(" "));
	$attributes[] = array(  'label'=>'Repo Grant', 
							'type' =>'raw', 'value'=> $model->isRepositoryGrant()? $imgChecked: CHtml::encode(" "));
	$this->widget('zii.widgets.CDetailView', array('data'=>$model,'attributes'=>$attributes) );

?>
