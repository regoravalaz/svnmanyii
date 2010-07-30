<?php

/**
 * This is the model class for table "svnmanager.users".
 *
 * The followings are the available columns in table 'svnmanager.users':
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property integer $admin
 * @property integer $repositorygrants
 * @property string $svnserve_password
 */
class SmyUser extends CActiveRecord
{
	
	const  Admin 				= 255;  // administrator magic number how code mapped how constant
	const  RepoGrants 			= 1;  	// administrator magic number how code mapped how constant
	public $newPassword 		= "";	// get new password from user
	public $retypePassword 		= "";	// password confirmation
	public $autorizePassword	= "";	// password for autorize the operation
	private $_oldname			= "";
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $scenario
	 */
	public function __construct($scenario='create')
	{
		parent::__construct($scenario);
	}	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmyUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'svnmanager.users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('admin, repositorygrants', 'numerical', 'integerOnly'=>true),
			array('name, newPassword, retypePassword, autorizePassword', 'length', 'max'=>32),
			array('email', 'length', 'max'=>128),
			array('autorizePassword, email, name', 'required', 'on'=>'create,update'),
			array('newPassword, retypePassword', 'required', 'on'=>'create'),
			array('newPassword', 'compare', 'compareAttribute'=>'retypePassword', 'message'=>'Retype Password must be repeated exactly New Password.', 'on'=>'update, create'),			
			array('id, name, email, admin, repositorygrants', 'safe', 'on'=>'search'),
			array('password, autorizePassword, oldname', 'safe', 'on'=>'update,create'),
			array('name', 'unique', 'on'=>'update,create'),
			
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"usergroups"=> array(self::HAS_MANY, 'SmyUserGroup', 'userid'),
			"userprivileges"=> array(self::HAS_MANY, 'SmyUserPrivilege', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Smy', 'Id'),
			'name' => Yii::t('Smy', 'Name'),
			'newPassword' => Yii::t('Smy', 'New Password'),
			'retypePassword' => Yii::t('Smy', 'Retype Password'),
			'autorizePassword' => Yii::t('Smy', 'Current Password'),
			'email' => Yii::t('Smy', 'Email'),
			'admin' => Yii::t('Smy', 'Admin'),
			'repositorygrants' => Yii::t('Smy', 'Repo Grants')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('admin',$this->admin);

		$criteria->compare('repositorygrants',$this->repositorygrants);

		$criteria->compare('svnserve_password',$this->svnserve_password,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	
	/**
	 * Valida el password suministrado
	 * @param string $password
	 * @return bool
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($password) === $this->password;
	}
	
	
	/**
	 * Obtiene el MD% Hash del password
	 * @param string $password
	 * @return string
	 */
	public function hashPassword($password)
	{
		return md5($password);
	}
	
	
	/**
	 * Check if is Administrative User
	 */
	public function isAdmin()
	{
		return ($this["admin"] == SmyUser::Admin);
	}
	
	
	/**
	 *  Check if  User has grants for handler repositories (create)
	 */
	public function isRepositoryGrant()
	{
		return ($this["repositorygrants"] == SmyUser::RepoGrants);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see base/CModel::beforeValidate()
	 */
	public function beforeValidate()
	{		
		if( parent::beforeValidate() )
		{
			$modelUserSesioned = SmyUser::model()->findbyPk( Yii::app()->user->id );
			if( $this->hashPassword($this->autorizePassword) == $modelUserSesioned["password"] ) return true;
			$this->addError("autorizePassword", "The password is invalid ");
		}  
		return false;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		$beforeSave = parent::beforeSave();
		if( trim($this->newPassword) != "" && trim($this->retypePassword) != "")  
		{
			$this->password = md5($this->newPassword);
			
			// when is new record
			if( $this->IsNewRecord ) 
			{
				return  $beforeSave && $this->createUser();
			}	
			// when is  updatind the current record
			return  $beforeSave && $this->updateUserPassword();	
		}
		
		// update apache file when only changed user name
		if( $this->IsNameChanged )
		{
			return  $beforeSave && $this->updateUserName();
		}	

		// update other attributes differentes to name and password
		return  $beforeSave;
	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::afterSave()
	 */
	public function afterSave()
	{
		Yii::app()->repoHandler->rebuildAccessFile();		
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::afterDelete()
	 */
	public function afterDelete()
	{
		Yii::app()->repoHandler->rebuildAccessFile();
	}
	
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	private function createUser()
	{
		$resp = Yii::app()->repoHandler->createApacheUser($this->name, $this->newPassword);
		$this->addError("name", Yii::app()->repoHandler->error );
		return $resp;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	private function updateUserPassword()
	{
		$resp = Yii::app()->repoHandler->updateApacheUser( $this->name, $this->newPassword);
		$this->addError("name", Yii::app()->repoHandler->error );
		return $resp;		
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	private function updateUserNameAndPassword()
	{
		$resp = Yii::app()->repoHandler->renameApacheUser( $this->oldname, $this->name);
		$resp = $resp && Yii::app()->repoHandler->updateApacheUser( $this->name, $this->newPassword);
		$this->addError("name", Yii::app()->repoHandler->error );
		return $resp;		
	}
		
	
	private function updateUserName()
	{
		$resp = Yii::app()->repoHandler->renameApacheUser( $this->oldname, $this->name);
		$this->addError("name", Yii::app()->repoHandler->error );
		return $resp;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getoldname()
	{
		return $this->_oldname;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function setoldname($value)
	{
		$this->_oldname = $value;
	}	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getIsNameChanged()
	{
		return ( trim($this->_oldname) != trim($this->name) );
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{
		// check exist how group owner
		if( SmyGroup::model()->find('adminid=:userid', array(':userid'=>$this->id)) )
		{
			$this->addError("id", "There are groups that have the user as owner, you must upgrade these groups first." );
			return false;
		}
		
		
		// delete group-user associations
		$usergroups = $this->usergroups;
		foreach( $usergroups as $usergroup )
		{
			$usergroup->delete();
		}
		
		//  delete privilege-user associations
		$userprivileges = $this->userprivileges;
		foreach( $userprivileges as $userprivilege )
		{
			$userprivilege->delete();
		}
		
		return parent::beforeDelete();
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::afterFind()
	 */
	public function afterFind()
	{
		$this->_oldname = $this->name;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::scopes()
	 */
	public function scopes()
	{
		return array(
			"OrderByNameAsc"=>array("order"=>"name ASC")
		);
	}
	

}