<?php

	/**
	 * Utilitary Web Class for View Pages
	 * @author astarot
	 *
	 */
	class SmyHtml
	{
		const PATH_BACK_PREFIX 				= "pathback"; 
		const PATH_BACK_HIDDEN 				= "pathbackid";
		const PATH_CLASS_STYLE 				= "pathback";
		const IMG_BACK_FOLDER 				= "images/BackFolder.png";
		const IMG_CURRENT_FOLDER 			= "images/CurrentFolder.png";
		const PATH_BACK_ROOT 				= "(root)";
		const FOLDER_ROOT 					= "/";
		const CSS_PATH_BACK_FOLDER 			= "pathback";
		const CSS_PATH_CURRENT_FOLDER 		= "pathcurrentfolder";
		const LABEL_DEFAULT_CHILDS_SELECT 	= "Select a Folder";
		
		
		/**
		 * 
		 * Enter description here ...
		 * @param string $folderName Folder Name
		 * @param string $position Position Folder in the Path
		 * @param string $class Name Css Class
		 * @param string $imgsrc Source Image from Forlder Icon
		 */
		public static function buildLinkFolder($folderName, $position, $class, $imgsrc)
		{
			$imageFolder =  CHtml::image(  $imgsrc, "");
			return 	CHtml::link( $imageFolder.$folderName, "#", 
								 array("id"=>SmyHtml::PATH_BACK_PREFIX."_".$position, "class"=>$class) );	
		}
		
		/**
		 * Build Link Folder for to back in the path
		 * @param string $folderName Folder Name
		 * @param string $position Position Folder in the Path
		 */
		public static function linkBackFolder($folderName, $position)
		{
			return SmyHtml::buildLinkFolder($folderName, $position, SmyHtml::CSS_PATH_BACK_FOLDER, SmyHtml::IMG_BACK_FOLDER);
		}
		
		/**
		 * Build Link Folder for to back in the path
		 * @param string $folderName Folder Name
		 * @param string $position Position Folder in the Path
		 */
		public static function linkCurrentFolder($folderName, $position)
		{
			return SmyHtml::buildLinkFolder($folderName, $position, SmyHtml::CSS_PATH_CURRENT_FOLDER, SmyHtml::IMG_CURRENT_FOLDER);	
		}		
	}