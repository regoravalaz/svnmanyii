<?php

	/**
	 * 
	 * Component for Subversion Handler
	 * @author astarot
	 *
	 */
	class SmyRepoHandler extends CComponent
	{
		const CMD_LANG 				= " LANG= ";
		const CMD_SVN_CONFIG_DIR 	= " --config-dir ";
		const CMD_SVN_CREATE		= " create ";
		const MSG_SVNADMIN_FAILED	= " SvnAdmin failed ";
		const MSG_CANT_CREATE_REPO 	= " Can't make repository ";
		const MSG_REPO_EXIST 		= " already exists. ";
		const MSG_REMOVE_REPO_FAIL	= " Removing failed ";
		const MSG_TRASH_REPO_FAIL	= " Moving (deleting) failed ";
		const MSG_FAIL_FILE_EXIST   = "Can't rename repository to already existing name!";
		const MSG_FAIL_TO_RENAME	= "Error renaming repository!";
		const MSG_FAIL_UNKNOW_ERROR	= "Unknow Error. Not defined for SVN Api.";
		const ARG_SVN_CREATE_PWD    = " -cmb ";
		const ARG_SVN_UPDATE_PWD    = " -mb ";
		const ARG_SVN_DELETE_PWD    = " -D ";
		const MSG_FAIL_UPDATE_PWD	= "Fail create/update apache password file!";
		const MSG_FAIL_DELETE_PWD	= "Fail delete apache password file!";
		const MSG_FAIL_OPENING_ACCES_FILE = "Fail SVN opening access file.";
		const MSG_FAIL_WRITE_ACCES_FILE = "Fail SVN Write access file";
			
		private $_configDir;
		private $_svnAdminCommand;
		private $_reposLocation;
		private $_error;
		private $_passwordFile;
		private $_accessFile;
		private $_htPasswordCommand;
		private $_svnCommand;
		private $_svnTrashLocation	= "";
		
		private $_svnStack = null;
		private $_svn = null;
		
		/**
		 * (non-PHPdoc)
		 * @see CController::init()
		 */
		public function init(){
			
		}
			
		
		/**
		 * Create Repository by Name
		 *  @return boolean
		 */
		public function createRepo($name)
		{
			$repoPath = escapeshellarg($this->_reposLocation . DIRECTORY_SEPARATOR . strtolower($name));

			if( file_exists($repoPath) )
			{
				$this->_error = SmyRepoHandler::MSG_SVNADMIN_FAILED . " to " . SmyRepoHandler::CMD_SVN_CREATE . $repoPath . SmyRepoHandler::MSG_REPO_EXIST;
				Yii::trace( $this->_error );
				return false;
			}
			else
			{
				$result =  	exec( SmyRepoHandler::CMD_LANG . $lang . "; " .
								 $this->_svnAdminCommand .  SmyRepoHandler::CMD_SVN_CONFIG_DIR . 
								 $this->_configDir .  SmyRepoHandler::CMD_SVN_CREATE . $repoPath );
				if( $result != "" )
				{
					$this->_error = SmyRepoHandler::MSG_SVNADMIN_FAILED . $result;
					Yii::trace( $this->_error );
					return false;
				}
			}
			return true;			
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $name
		 */
		public function deleteRepo($name)
		{
			$repoPath = escapeshellarg($this->_reposLocation . DIRECTORY_SEPARATOR . strtolower($name));
			
			if( $this->SvnTrashLocation == "")
			{
				// Delete the repository directory.
				if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
				{
		 			$ret = exec("rmdir /q /s $repoPath");
		 			if($ret!="")  return $this->LaunchErrorRemoveRepo($ret);
				} 
				else 
				{
					$ret = exec( SmyRepoHandler::CMD_LANG . $lang . ";rm -rf $repoPath 2>&1");
					if($ret!="")  return $this->LaunchErrorRemoveRepo($ret);
				}
			}
			else
			{
				// Move the repository to the trash directory.  Also, tack on the current
				// timestamp, in case a new repository with the same name is created and
				// subsequently removed.
				if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	
					// Filenames cannot contain colons (:) in Windows.
					$timestamp = str_replace(':', '_', date("c"));
					
					$arg_trash_path = escapeshellarg( $this->SvnTrashLocation . DIRECTORY_SEPARATOR . $name . "-" . $timestamp );
		 			$ret = exec("move /y $repoPath $arg_trash_path");
		 			if($ret!="")  return $this->LaunchErrorTrashRepo($ret);
				} 
				else 
				{
					$arg_trash_path = escapeshellarg( $this->SvnTrashLocation . DIRECTORY_SEPARATOR . $name . "-" . date("c") );
					$ret = exec("LANG=".$lang.";mv -f $repoPath $arg_trash_path 2>&1");
		 			if($ret!="") return $this->LaunchErrorTrashRepo($ret);

				}
			}
			return true;
		}
		
		
		
		/**
		 * Rebuild Subversion Access File
		 */
		public function rebuildAccessFile()
		{
			$accessfile = "############  Build with SVNMANYI   " .  date("D M j G:i:s ") . "\n\n";	
			
			//  Configuration from Subversion Groups
			$groups = SmyGroup::model()->findAll();
			if( $groups )
			{
				$accessfile .= "[groups]\n";
				foreach( $groups as $group)
				{
					$accessfile .= $group->name . " = ";
					$users = $group->users;
					if( $users )
					{
						$separator = "";
						foreach( $users as $user )
						{
							$accessfile .= $separator . $user->name;
							$separator = ", ";
						}
					}
					$accessfile .= "\n";
				}
			}
			
			
			$privileges = array();
			
			//  Review all Repositories
			$repositories = SmyRepositories::model()->findAll();
			if( $repositories )
			{
				foreach( $repositories as $repository)
				{
					// Review User Privileges by Repository
					$userPrivileges = $repository->userPrivileges;
					if( $userPrivileges )
					{
						foreach( $userPrivileges as $userPrivilege )
						{
							$key = "[".$repository->name.":".$userPrivilege->path."]";
							$privileges[$key] .= $userPrivilege->user->name . " = " . $userPrivilege->AccessFileKey . "\n";
						}
					}
					
					// Review Group Privileges by Repository
					$groupPrivileges = $repository->groupPrivileges;
					if( $groupPrivileges )
					{
						foreach( $groupPrivileges as $groupPrivilege )
						{
							$key = "[".$repository->name.":".$groupPrivilege->path."]";
							$privileges[$key] .= "@" . $groupPrivilege->group->name . " = " . $groupPrivilege->AccessFileKey . "\n";
						}
					}
				}
			}
			
			// printing configuration values into tmp variable
			foreach( $privileges as $key => $value ) $accessfile .= "\n" . $key . "\n" . $value;
				

			if (!$handle = fopen($this->_accessFile, 'w')) {
				return $this->LaunchErrorOpeningAccesFile();
			}
			if (fwrite($handle, $accessfile) === FALSE)
			{
				return $this->LaunchErrorWriteAccesFile();
			}
			fclose($handle);
			
			return true;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorOpeningAccesFile()
		{
 			$this->_error = SmyRepoHandler::MSG_FAIL_OPENING_ACCES_FILE;
 			Yii::trace($this->_error);
 			return false;	
		}
				
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorWriteAccesFile()
		{
 			$this->_error = SmyRepoHandler::MSG_FAIL_WRITE_ACCES_FILE;
 			Yii::trace($this->_error);
 			return false;	
		}		
		
		
		/**
		 * Create or Update password file to Apache style
		 * @param string $user  User
		 * @param string $password Password 
		 */
		public function createApacheUser($username, $password)
		{
			//Add user to svn password file
			//Escape special strings in htpasswd command
			$a_password = " " . escapeshellarg($password) . " ";
			$a_name = " " . escapeshellarg($username) . " ";
			$result  = "";
			if(!file_exists($this->_passwordFile))		
			{
				$result = exec( $this->_htPasswordCommand . SmyRepoHandler::ARG_SVN_CREATE_PWD . $this->_passwordFile .  $a_name . $a_password); 
			} 
			else 
			{
				$result = exec( $this->_htPasswordCommand . SmyRepoHandler::ARG_SVN_UPDATE_PWD . $this->_passwordFile .  $a_name . $a_password); 
			}
			if( trim($result) != "") return $this->LaunchErrorUpdateUserPassword();
			return true;
		}
		
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $username
		 * @param unknown_type $password
		 */
		public function updateApacheUser($username, $password)
		{
			if( !$this->deleteApacheUser($username) ) return false;
			if( !$this->createApacheUser($username, $password) ) return false;
			return true;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $username
		 * @param unknown_type $password
		 */
		public function renameApacheUser($oldname, $name)
		{
			$resp = file_put_contents($this->_passwordFile, preg_replace('/'.$oldname.':/', $name.':', 
															 file_get_contents($this->_passwordFile))) ;	
			if ($resp) return true;
			$this->LaunchErrorUpdateUserPassword();
			return false;
		}		
		
			/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $username
		 * @param unknown_type $password
		 */
		public function deleteApacheUser($username)
		{
			$result = exec($this->_htPasswordCommand .  SmyRepoHandler::ARG_SVN_DELETE_PWD . $this->_passwordFile . " " . escapeshellarg($username));
			if( trim($result) != "") return $this->LaunchErrorDeleteUserPassword(); 
			return true;
		}		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $reponame
		 */
		public function getRepoPath($reponame)
		{
			return escapeshellarg($this->getRepoPathNotScape($reponame));
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $reponame
		 */
		public function getRepoPathNotScape($reponame)
		{

			return $this->_reposLocation . DIRECTORY_SEPARATOR . strtolower($reponame);
		}
		
		/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $repoName
		 * @param unknown_type $pathSource
		 */
		public function getSubFolders($repoName, $pathSource)
		{
			return array("child1", "child2", "child3", "child4");
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $msg
		 */
		private function logInfo($msg)
		{
			Yii::log($msg, "info");
		}
		
		
		/**
		 * Retrive List Subfolders with Repository Name and the Parent Path
		 * @param string $repoName
		 * @param string $pathSource
		 * @return Array (list subfolderes) or Null when there are errors
		 */
		public function retrieveSubfolders($repoName, $parentPath)
		{
			require_once("VersionControl/SVN.php");
			//  configure el path access from repo + path to examine
			$repoPath = "file://" . $this->getRepoPathNotScape($repoName) . $parentPath;	
			if (strstr($repoPath, " ")) $repoPath = "\"".$repoPath."\"";

			$switches = array('config_dir' => $this->_configDir);
			$subfolders=array();		

			//  run svn command for retrieve childs folders
			if( $svnList = $this->SvnListObj->run( array($repoPath), $switches) )
			{
				foreach($svnList as $entry)
				{				
					if($entry['type']=='D')$subfolders[]=$entry['name'];
				}
			} 
			else 
			{				
				//  store errors in the process
				if (count($errs = $this->SvnStack->getErrors())) 
				{ 
			   		foreach ($errs as $err) 
			   		{
	    	        	$this->_error .=  " \n\r" . $err['message'];
	            		$this->_error .= " \n\rCommand used: " . $err['params']['cmd'];
	            		$this->_error .=  " \n\r\n\r";
	         		}
				}
				return array();
			}
			return 	$subfolders;
		}
		
		
		/**
		 * Get SvnObject from Svn Runtime, implement Singleton Object
		 */
		public function getSvnListObj()
		{
			require_once("VersionControl/SVN.php");
			
			$options = array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_ARRAY, 
							 'svn_path' => $this->_svnCommand);
			
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
				$options['svn_path'] = getenv("COMSPEC")." /C ". $options['svn_path'];

			if( $this->_svn == null )
			 	$this->_svn = VersionControl_SVN::factory('list', $options);
			
			return 	$this->_svn;		
		}
		
		
		/**
		 * Get Error Controler from Svn Runtime, implement Singleton Object
		 */
		public function getSvnStack()
		{
			require_once("VersionControl/SVN.php");
			if( $this->_svnStack == null) 
				$this->_svnStack = &PEAR_ErrorStack::singleton('VersionControl_SVN');
				
			return $this->_svnStack;
		}
			
		/**
		 * Rename the repository
		 * @param string $oldName Source Name
		 * @param string $newName Final Name
		 */
		public function renameRepo($oldName, $newName)
		{
			$oldPath = $this->_reposLocation . DIRECTORY_SEPARATOR . strtolower($oldName);
			$newPath = $this->_reposLocation . DIRECTORY_SEPARATOR . strtolower($newName);

			if( trim($oldName) != trim($newName))
			{
				if(file_exists($newPath)) return $this->LaunchErrorFileExistToRename();
	
				if(!rename($oldPath, $newPath)) return $this->LaunchErrorFileToRename();
			}
			
			return true;
		}

		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorUpdateUserPassword()
		{
 			$this->_error = SmyRepoHandler::MSG_FAIL_UPDATE_PWD;
 			Yii::trace($this->_error);
 			return false;	
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorDeleteUserPassword()
		{
 			$this->_error = SmyRepoHandler::MSG_FAIL_DELETE_PWD;
 			Yii::trace($this->_error);
 			return false;	
		}		
	
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorFileToRename()
		{
 			$this->_error = SmyRepoHandler::MSG_FAIL_TO_RENAME;
 			Yii::trace($this->_error);
 			return false;		
		}
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorFileExistToRename()
		{
 			$this->_error = SmyRepoHandler::MSG_FAIL_FILE_EXIST;
 			Yii::trace($this->_error);
 			return false;		
		}
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorTrashRepo($result)
		{
 			$this->_error = SmyRepoHandler::MSG_TRASH_REPO_FAIL . ": $result" ;
 			Yii::trace($this->_error);
 			return false;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		private function LaunchErrorRemoveRepo($result)
		{
 			$this->_error = SmyRepoHandler::MSG_SVNADMIN_FAILED . ": $result" ;
 			Yii::trace($this->_error);
 			return false;
		}
		
		
		/**
		 * Clear result of operations
		 */
		public function clearAll()
		{
			 $this->_error = null;
		}
		
		
		/**
		 * Get error message from operations
		 */
		public function getError()
		{
			return $this->_error;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getConfigDir()
		{
			return $this->_configDir;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setConfigDir($value)
		{
			$this->_configDir = trim($value);
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getSvnAdminCommand()
		{
			return $this->_svnAdminCommand;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setSvnAdminCommand($value)
		{
			$this->_svnAdminCommand = trim($value);
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getReposLocation()
		{
			return $this->_reposLocation;
		}
		
			
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setReposLocation($value)
		{
			$this->_reposLocation = trim($value);
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getPasswordFile()
		{
			return $this->_passwordFile;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setPasswordFile($value)
		{
			$this->_passwordFile = trim($value);
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getAccessFile()
		{
			return $this->_accessFile;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setAccessFile($value)
		{
			$this->_accessFile = trim($value);
		}		
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getHtPasswordCommand()
		{
			return $this->_htPasswordCommand;
		}
			
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setHtPasswordCommand($value)
		{
			$this->_htPasswordCommand = trim($value);
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getSvnCommand()
		{
			return $this->_svnCommand;
		}
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $value
		 */
		public function setSvnCommand($value)
		{
			$this->_svnCommand = trim($value);
		}
		
		
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function getSvnTrashLocation()
		{
			return $this->_svnTrashLocation;
		}
		
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param unknown_type $value
		 */
		public function setSvnTrashLocation($value)
		{
			$this->_svnTrashLocation = trim($value);
		}
	}