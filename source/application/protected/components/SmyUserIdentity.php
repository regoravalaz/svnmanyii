<?php

Yii::import("application.models.SmyUser");

/**
 * SmyUserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class SmyUserIdentity extends CUserIdentity
{
	//  atributos privados de la clase
	private $_id;
	private $_email;
	
	const RolAdministrative = "admin";
	const RolSingle = "single";
	
	/**
	 * Obtiene el Id
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	
	/**
	 * Establece el Id
	 * @param $value
	 * @return integer
	 */
	public function setId($value)
	{
		$this->_id = $value;
	}
	
	/**
	 * Obtiene el Email del Usuario
	 * @return string
	 */
	public function getEmail()
	{
		return $this->_email;
	}
	
	
	/**
	 * Establece el Email del Usuario
	 * @param string $value
	 */
	public function setEmail($value)
	{
		$this->_email = $value;
	}
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{	
		// la busqueda la configuramos case insensitive
		$user=SmyUser::model()->find('LOWER(name)=?',array( strtolower($this->username) ));
		
		if( $user === null )
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else if($user->validatePassword($this->password))
		{
			$this->_id = $user->id;
			$this->username = $user->name;
			
			//  se asignan los roles al usuario autenticado
			$auth = Yii::app()->authManager;
			if( $user->isAdmin() && !$auth->isAssigned( SmyUserIdentity::RolAdministrative, $this->_id))
			{
				$auth->assign( SmyUserIdentity::RolAdministrative,$this->_id);
				Yii::app()->authManager->save();
			}
			else if ( !$auth->isAssigned( SmyUserIdentity::RolSingle, $this->_id) )
			{
				$auth->assign( SmyUserIdentity::RolSingle,$this->_id);
				Yii::app()->authManager->save();	
			}	
			
			$this->errorCode = self::ERROR_NONE;
		}
		else
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
	
		return $this->errorCode == self::ERROR_NONE;

	}
}