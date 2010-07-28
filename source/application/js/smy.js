var Smy = {};

Smy.UserPrivileges = {};
Smy.UserPrivileges.PATH_CLASS_STYLE 	= "pathback";
Smy.UserPrivileges.PATH_BACK_HIDDEN 	= "pathbackid";
Smy.UserPrivileges.AJAX_URL_CHILDPATHS	= "/svnmanyii/index.php?r=smyUserPrivilege/childPaths";
Smy.UserPrivileges.AJAX_URL_PATHBACK 	= "/svnmanyii/index.php?r=smyUserPrivilege/processPathBack";
Smy.UserPrivileges.CONTAINER_FOLDER_ID  = "containerFolderPath";
Smy.UserPrivileges.REPO_NAME_ID			= "reponame";
Smy.UserPrivileges.attachEventBackFolders = function()
											{
												jQuery('.'+ this.PATH_CLASS_STYLE  ).live('click',  function()
																									{			
																										var up = Smy.UserPrivileges;
																										$("#" + up.PATH_BACK_HIDDEN).val( this.id );
																										jQuery.ajax( {	'type':'POST',
																														'url':up.AJAX_URL_PATHBACK,
																														'cache':false,
																														'data':jQuery(this).parents("form").serialize(),
																														'success':function(html)
																														{
																															jQuery("#" + up.CONTAINER_FOLDER_ID).html(html)
																														}
																													}
																										);
																										return false;
																										//end function
																									}
												);
												// end function
											};
Smy.UserPrivileges.updateRepoNameFromList = function(e)
											{
												var up = Smy.UserPrivileges;
												$("#" + up.REPO_NAME_ID	).val( e.options[e.selectedIndex].text );
												
												if( e.selectedIndex > 0 )
												{
													jQuery.ajax({	'type':'POST',
																	 'url':up.AJAX_URL_CHILDPATHS,
																	 'cache':false,
																	 'data':jQuery(e).parents("form").serialize(),
																	 'success':function(html)
																	 {
																		jQuery("#" + up.CONTAINER_FOLDER_ID).html(html);
																	 }
																});													
												}
												else
												{
													jQuery("#" + up.CONTAINER_FOLDER_ID).html("");
												};
											};


Smy.GroupPrivileges = {};
Smy.GroupPrivileges.PATH_CLASS_STYLE 	= "pathback";
Smy.GroupPrivileges.PATH_BACK_HIDDEN 	= "pathbackid";
Smy.GroupPrivileges.AJAX_URL_CHILDPATHS	= "/svnmanyii/index.php?r=smyGroupPrivilege/childPaths";
Smy.GroupPrivileges.AJAX_URL_PATHBACK 	= "/svnmanyii/index.php?r=smyGroupPrivilege/processPathBack";
Smy.GroupPrivileges.CONTAINER_FOLDER_ID  = "containerFolderPath";
Smy.GroupPrivileges.REPO_NAME_ID			= "reponame";
Smy.GroupPrivileges.attachEventBackFolders = function()
											{
												jQuery('.'+ this.PATH_CLASS_STYLE  ).live('click',  function()
																									{			
																										var up = Smy.GroupPrivileges;
																										$("#" + up.PATH_BACK_HIDDEN).val( this.id );
																										jQuery.ajax( {	'type':'POST',
																														'url':up.AJAX_URL_PATHBACK,
																														'cache':false,
																														'data':jQuery(this).parents("form").serialize(),
																														'success':function(html)
																														{
																															jQuery("#" + up.CONTAINER_FOLDER_ID).html(html)
																														}
																													}
																										);
																										return false;
																										//end function
																									}
												);
												// end function
											};

Smy.GroupPrivileges.updateRepoNameFromList = function(e)
											{
												var up = Smy.GroupPrivileges;
												$("#" + up.REPO_NAME_ID	).val( e.options[e.selectedIndex].text );
												
												if( e.selectedIndex > 0 )
												{
													jQuery.ajax({	'type':'POST',
																	 'url':up.AJAX_URL_CHILDPATHS,
																	 'cache':false,
																	 'data':jQuery(e).parents("form").serialize(),
																	 'success':function(html)
																	 {
																		jQuery("#" + up.CONTAINER_FOLDER_ID).html(html);
																	 }
																});													
												}
												else
												{
													jQuery("#" + up.CONTAINER_FOLDER_ID).html("");
												};
											};
