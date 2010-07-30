<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/svnmanyii.css" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/smy.js"></script> 
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header" style="background-color:#485870;">
		<!-- header -->
		<!--  <div id="logo">SMY&nbsp;-&nbsp;<?php echo CHtml::encode(Yii::app()->name); ?></div>-->
		<img src="images/LogoWebDarkSvnManYii.png" alt=""  style="padding:0px;"/>
	</div>
		<?php
		?>
	<div >
		<?php
			if( Yii::app()->user->checkAccess('admin') )
			{
				$this->widget('ext.JQuerySlideTopMenu.JQuerySlideTopMenu', 
				
				array( 'items'=>array(  array('label'=>'Home', 'url'=>array('site/index')),
				
								        array('label'=>'Repositories', 'url'=>'#', 'subs'=>array(
								                array('label'=>'Add New', 'url'=>array('/smyRepositories/create')),
								                array('label'=>'List', 'url'=>array('/smyRepositories')),
								                array('label'=>'Manage', 'url'=>array('/smyRepositories/admin')),	
								                array('label'=>'Privileges', 'url'=>'#','subs'=>array(							                
									                array('label'=>'Groups', 'url'=>array('/smyGroupPrivilege')),
									                array('label'=>'Users', 'url'=>array('/smyUserPrivilege')),
								                ) ),
								            ), 'visible'=>!Yii::app()->user->isGuest),
								            
								        array('label'=>'Groups', 'url'=>'#', 'subs'=>array(
								        		array('label'=>'Add New', 'url'=>array('/smyGroup/create')),
								                array('label'=>'List', 'url'=>array('/smyGroup')),
								                array('label'=>'Manage', 'url'=>array('/smyGroup/admin')),
								                array('label'=>'Users by Group', 'url'=>array('/smyUserGroup')),
								                array('label'=>'Repositories Privileges', 'url'=>array('/smyGroupPrivilege')),
								        	), 'visible'=>!Yii::app()->user->isGuest),
								        	
								        array('label'=>'Users', 'url'=>'#', 'subs'=>array(
								        		array('label'=>'Add New', 'url'=>array('/smyUser/create')),
								                array('label'=>'List', 'url'=>array('/smyUser')),
								                array('label'=>'Manage', 'url'=>array('/smyUser/admin')),
								                array('label'=>'Users by Group', 'url'=>array('/smyUserGroup')),
								                array('label'=>'Repositories Privileges', 'url'=>array('/smyUserPrivilege')),
								        	), 'visible'=>!Yii::app()->user->isGuest),
								        	
								        array('label'=>'My profile', 'url'=>array('smyUser/view&id='. Yii::app()->user->id), 'visible'=>!Yii::app()->user->isGuest),
										array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
										array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest)
								    ),
				));
				/*$this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Home', 				'url'=>array('/site/index')),
						array('label'=>'User Privileges', 	'url'=>array('/smyUserPrivilege')),
						array('label'=>'Group Privileges', 	'url'=>array('/smyGroupPrivilege')),
						array('label'=>'Repositories', 		'url'=>array('/SmyRepositories')),	
						array('label'=>'Users for Group', 	'url'=>array('/smyUserGroup')),				
						array('label'=>'Groups', 			'url'=>array('/smyGroup')),
						array('label'=>'Users', 			'url'=>array('/smyUser')),
						array('label'=>'Login', 			'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				));*/
			}
			else if( !Yii::app()->user->checkAccess('admin') && Yii::app()->user->checkAccess('single') )
			{
				
				$this->widget('ext.JQuerySlideTopMenu.JQuerySlideTopMenu', 
					array( 'items'=>array(  
						array('label'=>'Home', 'url'=>array('site/index')),
						array('label'=>'Users', 'url'=>array('smyUser/view&id=' . Yii::app()->user->id) ),
						array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),						
					  	array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					)
				));				
				/**$this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Home', 	'url'=>array('/site/index')),
						array('label'=>'Users', 'url'=>array('/smyUser/view&id=' . Yii::app()->user->id) ),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				));**/
			}			
			else 
			{
				
			$this->widget('ext.JQuerySlideTopMenu.JQuerySlideTopMenu', 
				array( 'items'=>array(  
					array('label'=>'Home', 'url'=>array('site/index')),
				  	array('label'=>'Login', 'url'=>array('site/login')),
				)
			));
				/*$this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Home', 	'url'=>array('/site/index')),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest)
					),
				));*/
			} 
		?>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by <br/><a href="http://www.koiosoft.com/" rel="external">Koiosoft</a>.
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->
</body>
</html>