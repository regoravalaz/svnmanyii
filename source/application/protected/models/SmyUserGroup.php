<?php

/**
 * This is the model class for table "svnmanager.usersgroups".
 *
 * The followings are the available columns in table 'svnmanager.usersgroups':
 * @property integer $id
 * @property integer $userid
 * @property integer $groupid
 */
class SmyUserGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmyUserGroup the static model class
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
		return 'svnmanager.usersgroups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, groupid', 'numerical', 'integerOnly'=>true),
			array('userid', 'numerical', 'integerOnly'=>true, 'allowEmpty'=> false, 'message'=>'User selection is required.'),
			array('groupid', 'numerical', 'integerOnly'=>true, 'allowEmpty'=> false, 'message'=>'Group selection is required.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userid, groupid', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'SmyUser', 'userid'),
			'group' => array(self::BELONGS_TO, 'SmyGroup', 'groupid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userid' => 'Userid',
			'groupid' => 'Groupid',
			'groupname' => 'Group',
			'username' => 'User',		
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

		$criteria->compare('userid',$this->userid);

		$criteria->compare('groupid',$this->groupid);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getusername()
	{
		return $this->user->name;
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
		
		
}