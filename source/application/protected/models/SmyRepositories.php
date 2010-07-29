<?php

/**
 * This is the model class for table "svnmanager.repositories".
 *
 * The followings are the available columns in table 'svnmanager.repositories':
 * @property integer $id
 * @property string $name
 * @property integer $ownerid
 * @property string $description
 */
class SmyRepositories extends CActiveRecord
{

	private $_oldname;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmyRepositories the static model class
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
		return 'svnmanager.repositories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ownerid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('description', 'length', 'max'=>128),
			array('name,description', 'required', 'on'=>'update,create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, ownerid, description', 'safe', 'on'=>'search'),
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
			'owneruser' => array(self::BELONGS_TO, 'SmyUser', 'ownerid'),
			'subDesc' => array(self::HAS_ONE, 'SmyRepoDescriptions', 'repo_id'),
			'userPrivileges' => array(self::HAS_MANY, 'SmyUserPrivilege', 'repositoryid'),
			'groupPrivileges' => array(self::HAS_MANY, 'SmyGroupPrivilege', 'repositoryid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'ownerid' => 'Ownerid',
			'description' => 'Description',
			'ownername' => 'Owner'
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

		$criteria->compare('ownerid',$this->ownerid);

		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	
	/**
	 * Description from repository
	 */
	public function getdescription()
	{
		return $this->subDesc->description;
	}
	
	
	/**
	 * Set Description from Repository
	 * @param string $value
	 */
	public function setdescription($value)
	{
	
		if( $this->subDesc == null )
		{	
			$this->subDesc = new SmyRepoDescriptions();
		}
		$this->subDesc->description = $value;

	}
	
	
	/**
	 * Get the User owner from this repository
	 */
	public function getowner()
	{
		return $this->owneruser;
	}	
	
	
	/**
	 * Get the Name from owner from this repository
	 */
	public function getownername()
	{
		return $this->owner->name;
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
	 * @param unknown_type $value
	 */
	public function setoldname($value)
	{
		$this->_oldname = $value;
	}
	
	/**
	 * Previous Evento to Delete Record
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{
		// delete privilege-users associations
		$userprivileges = $this->userPrivileges;
		foreach( $userprivileges as $userprivilege )
		{
			$userprivilege->delete();
		}
		
		//  delete privilege-group associations
		$groupprivileges = $this->groupPrivileges;
		foreach( $groupprivileges as $groupprivilege )
		{
			$groupprivilege->delete();
		}		
		
		$beforeDelete = parent::beforeDelete();
		if($this->subDesc != null )
		{
			$beforeDelete = $beforeDelete && $this->subDesc->delete();
		}		
		if( Yii::app()->repoHandler->deleteRepo( $this->name ) )
		{
			return $beforeDelete;
		}		
		$this->addError("name", Yii::app()->repoHandler->error );
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		$beforeSave = parent::beforeSave();	
		if( $this->getIsNewRecord() )
		{
			return $beforeSave && Yii::app()->repoHandler->createRepo( $this->name );
		}	
		if( Yii::app()->repoHandler->renameRepo( $this->oldname, $this->name ) )
		{
			return $beforeSave;
		}
		$this->addError("name", Yii::app()->repoHandler->error );	
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::onAfterSave()
	 */
	public function afterSave()
	{
		parent::afterSave();
		if( $this->getIsNewRecord() )
		{
			$this->subDesc->repo_id = $this->id;
		} 
		$this->subDesc->save();
	
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