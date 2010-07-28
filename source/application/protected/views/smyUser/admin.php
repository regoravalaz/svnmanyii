<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SmyUser', 'url'=>array('index')),
	array('label'=>'Create SmyUser', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('smy-user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
	$imgChecked =  'CHtml::image("images/Check-icon.png","checked", array("style"=>"width:12px;height:12px;"))';
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'smy-user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array( 'name'=>'id', 'header'=>'Id'),
		'name',
		'email',
		array(            // display 'create_time' using an expression
            'name'=>'admin',
			'type' =>'raw',
			'header'=>'Admin',
            'value'=>'$data->isAdmin()?' .$imgChecked . ':  CHtml::encode(" ")'
        ),
		array(            // display 'create_time' using an expression
            'name'=>'repositorygrants',
			'type' =>'raw',
			'header'=>'Repo Grants',
            'value'=>'$data->isRepositoryGrant()?' .$imgChecked . ':  CHtml::encode(" ")',
        ),
		array(
			'header'=>'Operations',
			'class'=>'CButtonColumn',
		),
	),
)); ?>
