<?php
$this->breadcrumbs=array(
	'Groups'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('smy-group-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Groups</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'smy-group-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		array(            // display 'create_time' using an expression
            'name'=>'adminid',
			'type' =>'raw',
			'header'=>'Administrator',
            'value'=>'CHtml::link(CHtml::encode($data->admin->name),
									 array(\'smyUser/view\',\'id\'=>$data->admin->id))',
        ),		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
