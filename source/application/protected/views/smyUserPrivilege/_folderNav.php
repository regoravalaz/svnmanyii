<div id="containerFolderPath" style="display:inline;"> 
<?php 

	// Prepare parent path for split in folders
	$parent = trim(str_replace("//", "/", $parent));
	$parent = $parent==""?"/":$parent;
	echo CHtml::hiddenField("path", $parent);

	if( $parent != "/" )
	{
		$folders = split("/", SmyHtml::PATH_BACK_ROOT . $parent);
	}
	else 
	{
		$folders = array(SmyHtml::PATH_BACK_ROOT);
	}

	/**
	 * Show navigation path to current folder
	 */
	$count = count($folders) - 1;
	for( $i = 0; $i < $count; $i++ )  
	{
		echo SmyHtml::linkBackFolder($folders[$i], $i);
	}
	echo SmyHtml::linkCurrentFolder($folders[$count], $count);				
	echo CHtml::hiddenField( SmyHtml::PATH_BACK_HIDDEN, "");
	
	/**
	 * show dropdown with folder childs
	 * previous prepare de data for widget
	 */  
	$count = count($childs);
	$childsFolder = array( "" => SmyHtml::LABEL_DEFAULT_CHILDS_SELECT );
	for( $i = 0; $i < $count; $i++)
	{
		$childsFolder["/".$childs[$i]] = $childs[$i];
	}
	echo CHtml::dropDownList( 	'selectPath','', 
								$childsFolder,
								array(  'ajax' 	=> array(
										'type'	=>'POST',
								 		'update'=>'#containerFolderPath',
										'url'	=>CController::createUrl('smyUserPrivilege/childPaths')) ) );	
?>
</div>
<script type="text/javascript">
<!--
	Smy.UserPrivileges.attachEventBackFolders();
//-->
</script>
