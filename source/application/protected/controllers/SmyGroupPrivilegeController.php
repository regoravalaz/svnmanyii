<?php


/**
 * 
 * Enter description here ...
 * @author astarot
 *
 */
class SmyGroupPrivilegeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'view' and 'update' actions
				'actions'=>array('update','view'),
				'roles'=>array('single'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','view','update','index','childPaths', 'processPathBack'),
				'roles'=>array('admin'),
			),
			array(	'deny',  // deny not authenticated
					'actions'=>array('admin','delete','create','view','update','index'),
					'users'=>array('*'))
		);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function actionChildPaths()
	{
		$newpath = $_POST["path"] . $_POST["selectPath"];
		if($newpath == "") 
		{
			$newpath = "/";
		}
		$subFolders =Yii::app()->repoHandler->retrieveSubfolders($_POST["reponame"], $newpath);
		$dataPath = array( "parent" => $newpath, "childs"=>$subFolders, "reponame"=>$_POST["reponame"]); 
		$this->renderPartial( '_folderNav', $dataPath, false, false );	
		
		Yii::log( CVarDumper::dumpAsString($_POST) , "info");
		Yii::log( CVarDumper::dumpAsString($dataPath) , "info");		
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function actionProcessPathBack()
	{
		$pos = str_replace(SmyHtml::PATH_BACK_PREFIX . "_", "", $_POST["pathbackid"]);
		$newpath = join("/", array_slice(split("/", trim($_POST["path"])), 0, $pos + 1));
		$subFolders =Yii::app()->repoHandler->retrieveSubfolders($_POST["reponame"], $newpath);
		
		$dataPath = array( "parent" => $newpath, "childs"=>$subFolders, "reponame"=>$_POST["reponame"]); 
		$this->renderPartial('_folderNav', $dataPath, false, false );
	}	
	

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new SmyGroupPrivilege;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SmyGroupPrivilege']))
		{
			$model->attributes=$_POST['SmyGroupPrivilege'];
			if(isset($_POST['path'])) $model->path = $_POST['path'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SmyGroupPrivilege']))
		{
			$model->attributes=$_POST['SmyGroupPrivilege'];
			if(isset($_POST['path'])) $model->path = $_POST['path'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'subfolders'=>Yii::app()->repoHandler->getSubFolders($model->repoName, $model->path)
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('SmyGroupPrivilege');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SmyGroupPrivilege('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SmyGroupPrivilege']))
			$model->attributes=$_GET['SmyGroupPrivilege'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=SmyGroupPrivilege::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='smy-group-privilege-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
