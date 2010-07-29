<?php

/**
 * This is the model class for table "svnmanager.groups".
 *
 * The followings are the available columns in table 'svnmanager.groups':
 * @property integer $id
 * @property string $name
 * @property integer $adminid
 */
class SmyGroup extends CActiveRecord
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmyGroup the static model class
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
		return 'svnmanager.groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adminid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('name', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, adminid', 'safe', 'on'=>'search'),
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
			'admin' => array(self::BELONGS_TO, 'SmyUser', 'adminid'),
			'users'=>array(self::MANY_MANY, 'SmyUser',
				                'svnmanager.usersgroups(userid, groupid)'),	
			'usergroups'=>array(self::HAS_MANY, 'SmyUserGroup', 'groupid'),
			'groupprivileges'=>array(self::HAS_MANY, 'SmyGroupPrivilege', 'groupid'),	
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
			'adminid' => 'Adminid',
			'admin' => 'Administrator'
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

		$criteria->compare('adminid',$this->adminid);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
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
	 * (non-PHPdoc)
	 * @see db/ar/CActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{
		// delete group-user associations
		$usergroups = $this->usergroups;
		foreach( $usergroups as $usergroup )
		{
			$usergroup->delete();
		}
		
		//  delete privilege-group associations
		$groupprivileges = $this->groupprivileges;
		foreach( $groupprivileges as $groupprivilege )
		{
			$groupprivilege->delete();
		}
		
		return parent::beforeDelete();
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