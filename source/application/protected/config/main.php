<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'SvnManYii',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.mail.*'
	),
	
	'modules'=>array(
			'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'12345678',
			),
		),	

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'repoHandler'=>array(
			'class'				=>'SmyRepoHandler',
			'ConfigDir'			=>'/home/astarot/Documents/subversion/conf',
			'PasswordFile'		=>'/home/astarot/Documents/subversion/conf/passwd',
			'AccessFile'		=>'/home/astarot/Documents/subversion/conf/authz',
			'ReposLocation'		=>'/home/astarot/Documents/subversion/repositories',
			'SvnAdminCommand'	=>'/usr/bin/svnadmin',
			'SvnCommand'		=>'/usr/bin/svn',
			'HtPasswordCommand'	=>'/usr/bin/htpasswd',
			'SvnTrashLocation'	=>''
		),
		'mail'=>array(
			'class'				=>'ext.mail.Mail',
			'transportType' 	=> 'smtp',
			'transportOptions'	=> array(
										'host'=>'xxx,
										'encryption'=>'xx',
										'port'=>'xxx',
										'username'=>'xx',									
										'password'=>'x'
									),
			'viewPath' 			=> 'application.views.mail',
			'debug' 			=> false
		),
		
		
		/*'authManager'=>array(
			'class'=>'CPhpAuthManager'
		),	*/
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'db-original'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=svnmanager',
			'username' => 'svnmanager',
			'password' => '12345678',
			'charset' => 'utf8'
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'rogerzavala@gmail.com',
	)
);