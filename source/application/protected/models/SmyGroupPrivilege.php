<?php

/**
 * This is the model class for table "svnmanager.groupprivileges".
 *
 * The followings are the available columns in table 'svnmanager.groupprivileges':
 * @property integer $id
 * @property integer $groupid
 * @property integer $repositoryid
 * @property integer $access
 * @property string $path
 */
class SmyGroupPrivilege extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmyGroupPrivilege the static model class
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
		return 'svnmanager.groupprivileges';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('groupid, access', 'numerical', 'integerOnly'=>true),
			array('groupid', 'numerical', 'integerOnly'=>true, 'allowEmpty'=> false, 'message'=>'Group selection is required.'),
			array('repositoryid', 'numerical', 'integerOnly'=>true, 'allowEmpty'=> false, 'message'=>'Repository selection is required.'),			
			array('repositoryid, groupid', 'required', 'on'=>'update,create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, groupid, access, path', 'safe', 'on'=>'search'),			
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
			'group' => array(self::BELONGS_TO, 'SmyGroup', 'groupid'),
			'repository' => array(self::BELONGS_TO, 'SmyRepositories', 'repositoryid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'groupid' => 'GroupId',
			'repositoryid' => 'Repositoryid',
			'access' => 'Access',
			'path' => 'Path',
			'reponame' => 'Repository',
			'groupname' => 'Group',
			'accestype' => 'Access Type'
		);
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getreponame()
	{
		return $this->repository->name;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getgroupname()
	{
		return $this->group->name;
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

		$criteria->compare('groupid',$this->groupid);

		$criteria->compare('repositoryid',$this->repositoryid);

		$criteria->compare('access',$this->access);

		$criteria->compare('path',$this->path,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	
	/**
	 *  Get Human Readly the access value
	 *  0 - Zero access.
	 *  1 - Only Read
	 *  2 - Only Write
	 *  3 - Full access, read and write
	 */
	public function getaccestype()
	{
		$accessList = SmyUserPrivilege::retriveAccessList();
		return $accessList[$this->access]["name"];
	}	
	
	/**
	 * Retrieve list Access Privileges
	 * return Array
	 */
	public static function retriveAccessList()
	{
		return array( "0"=>array("id"=>"0", "name"=>"Zero Permission"),
					  "1"=>array("id"=>"1", "name"=>"Only Read"),
					  "2"=>array("id"=>"2", "name"=>"Only Write"),
					  "3"=>array("id"=>"3", "name"=>"Full (read and write)")
		);
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
	 * 
	 * Enter description here ...
	 */
	public function getAccessFileKey()
	{
		switch($this->access)
		{
			case "0":
				return "";
				break;
			case "1":
				return "r";
				break;
			case "2":
				return "w";
				break;
			case "3":
				return "rw";
				break;				
		}
	}	
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::afterDelete()
	 */
	public function afterDelete()
	{
		Yii::app()->repoHandler->rebuildAccessFile();
	}	
		
}