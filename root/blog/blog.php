<?
	include ($_SERVER["DOCUMENT_ROOT"].'/blog/config.php');
	
	###########################################################################
	#	DB connect
	$_connect = mysql_connect($_db_host , $_db_user, $_db_pass) or die ('<div class="error">Ашибка! Спасяйся кто может!</div><br />' . mysql_error());
	mysql_select_db($_db_name, $_connect) or die ('<div class="error">База все. RIP</div><br />' . mysql_error());
	mysql_set_charset('utf8',$_connect); 
	
	
class User {

	var $_users_query;
	var $_user_query;
	var $_blog_users = array();
	var $_blog_user = array();
	
	function __construct() {
		return $this->getList();
	}

	###########################################################################
	#	Get Users list
	function getList() {
	
		$_users_query = mysql_query("SELECT ID,LOGIN FROM blog_users ORDER BY ID DESC");

		while ($row = mysql_fetch_assoc($_users_query)):
			$_blog_users[$row["ID"]] = $row['LOGIN'];
		endwhile;
		
		return $_blog_users;
	}
	
	###########################################################################
	#	Get User
	function getByID($_user_id) {
	
		$_user_query = mysql_query("SELECT * FROM blog_users WHERE ID = ".$_user_id);

		while ($row = mysql_fetch_assoc($_users_query)):
			$_blog_user = array(
							'LOGIN' => $row['LOGIN'],
							'PASS' => $row['PASS'],
							'EMAIL' => $row['EMAIL']);
		endwhile;
		
		return $_blog_user;
	}
}

	
class Blog {

	var $_posts_query;
	var $_post_query;
	var $_blog_posts = array();
	var $_blog_post = array();
	var $_blog_users;
	var $arrFields = array();
	var $retval;
	
	function __construct() {
		return $this->getList();
	}
	
	###########################################################################
	#	Blog posts
	function getList() {
	
		$_posts_query = mysql_query("SELECT * FROM blog_posts ORDER BY ID DESC");

		$_blog_users = User::getList();
		
		###########################################################################
		#	Get Posts
		while ($row = mysql_fetch_assoc($_posts_query)):
			$_blog_posts[] = array(
								'ID' => $row["ID"], 
								'TITLE' => $row['TITLE'], 
								'USER' => $_blog_users[$row['USER']], 
								'DATE' => $row['DATE'], 
								'TEXT' => $row['TEXT'], 
								'TAGS' => $row['TAGS']);
		endwhile;
		
		return $_blog_posts;
	}
	
	###########################################################################
	#	Blog item
	function getByID($_post_id = null) {
		
		if ($_post_id):
			$_post_query = mysql_query("SELECT * FROM blog_posts WHERE ID = ".$_post_id);
		endif;
		
		$_blog_users = User::getList();
		
		###########################################################################
		#	Get Post
		while ($row = mysql_fetch_assoc($_post_query)):
			$_blog_post = array(
								'ID' => $row["ID"], 
								'TITLE' => $row['TITLE'], 
								'USER' => $_blog_users[$row['USER']], 
								'DATE' => $row['DATE'], 
								'TEXT' => $row['TEXT'], 
								'DETAIL_TEXT' => $row['DETAIL_TEXT'], 
								'TAGS' => $row['TAGS']);
		endwhile;

		return $_blog_post;
	}
	
	###########################################################################
	#	Add post
	function Add($arrFields) {
	
		$arrFields["ACTIVE"] = isset($arrFields["ACTIVE"]) && $arrFields["ACTIVE"] === "N"? "N": "Y";
		$arrFields["USER"] = isset($arrFields["USER"])? $arrFields["USER"]: "1";
		$arrFields["DATE"] = isset($arrFields["DATE"])? $arrFields["DATE"]: date('Y-m-d');
		$arrFields["TITLE"] = isset($arrFields["TITLE"])? $arrFields["TITLE"] : "";
		$arrFields["TEXT"] = isset($arrFields["TEXT"])? $arrFields["TEXT"] : "";
		$arrFields["DETAIL_TEXT"] = isset($arrFields["DETAIL_TEXT"])? $arrFields["DETAIL_TEXT"] : "";
		$arrFields["TAGS"] = isset($arrFields["TAGS"])? $arrFields["TAGS"] : "";
		
		$retval = mysql_query("INSERT INTO test.blog_posts (ID,ACTIVE,USER,DATE,TITLE,TEXT,DETAIL_TEXT,TAGS) VALUES (NULL ,'$arrFields[ACTIVE]','$arrFields[USER]','$arrFields[DATE]','$arrFields[TITLE]','$arrFields[TEXT]','$arrFields[DETAIL_TEXT]','$arrFields[TAGS]')");
		
		if(!$retval):
			die('<div class="error">Запись не смогла сама себя записать в базу.</div><br />' . mysql_error());
		else:
			return 'ok';
		endif;
		
	}
	
	###########################################################################
	#	Delete post
	function Delete($_post_id = null) {
	
		$retval = mysql_query('DELETE FROM blog_posts WHERE ID='.$_post_id);
		
		if(!$retval ):
			die('<div class="error">Запись не смогла сама себя удалить из базы.</div><br />' . mysql_error());
		else:
			return 'ok';
		endif;
	}
	
	###########################################################################
	#	Edit post
	function Update($_post_id = null,$arrFields) {
	
		$arrFields["ACTIVE"] = isset($arrFields["ACTIVE"]) && $arrFields["ACTIVE"] === "N"? "N": "Y";
		$arrFields["USER"] = isset($arrFields["USER"])? $arrFields["USER"]: "1";
		$arrFields["DATE"] = isset($arrFields["DATE"])? $arrFields["DATE"]: date('Y-m-d');
		$arrFields["TITLE"] = isset($arrFields["TITLE"])? $arrFields["TITLE"] : "";
		$arrFields["TEXT"] = isset($arrFields["TEXT"])? $arrFields["TEXT"] : "";
		$arrFields["DETAIL_TEXT"] = isset($arrFields["DETAIL_TEXT"])? $arrFields["DETAIL_TEXT"] : "";
		$arrFields["TAGS"] = isset($arrFields["TAGS"])? $arrFields["TAGS"] : "";
		
		$retval = mysql_query("UPDATE test.blog_posts SET ACTIVE='$arrFields[ACTIVE]',USER='$arrFields[USER]',DATE='$arrFields[DATE]',TITLE='$arrFields[TITLE]',TEXT='$arrFields[TEXT]',DETAIL_TEXT='$arrFields[DETAIL_TEXT]',TAGS='$arrFields[TAGS]' WHERE ID=".$_post_id);
		
		if(!$retval):
			die('<div class="error">Запись не смогла сама себя обновить.</div><br />' . mysql_error());
		else:
			return 'ok';
		endif;
		
	}
}

class Comments {

	var $_comm_query;
	var $arrFields = array();
	var $retval;

	function __construct() {
		return $this->getList();
	}

	function getList($_post_id = null) {
	
		$_comm_query = mysql_query('SELECT * FROM blog_comments WHERE BLOG_POST_ID='.$_post_id);
		
		$_post_comments = array();
		
		###########################################################################
		#	Get Comments
		while ($row = mysql_fetch_assoc($_comm_query)):
			$_post_comments[] = array(
								'ID' => $row["ID"], 
								'USER_NAME' => $row['USER_NAME'], 
								'USER_MAIL' => $row['USER_MAIL'], 
								'DATE' => $row['DATE'], 
								'TEXT' => $row['TEXT']);
		endwhile;
		
		return $_post_comments;
		
	}
	
	function Add($arrFields) {
	
		$arrFields["ACTIVE"] = isset($arrFields["ACTIVE"]) && $arrFields["ACTIVE"] === "N"? "N": "Y";
		$arrFields["BLOG_POST_ID"] = isset($arrFields["BLOG_POST_ID"])? $arrFields["BLOG_POST_ID"]: "";
		$arrFields["USER_NAME"] = isset($arrFields["USER_NAME"])? $arrFields["USER_NAME"]: "";
		$arrFields["USER_MAIL"] = isset($arrFields["USER_MAIL"])? $arrFields["USER_MAIL"] : "";
		$arrFields["DATE"] = isset($arrFields["DATE"])? $arrFields["DATE"]: date('Y-m-d');
		$arrFields["TEXT"] = isset($arrFields["TEXT"])? $arrFields["TEXT"] : "";
		
		$retval = mysql_query("INSERT INTO test.blog_comments (ID,BLOG_POST_ID,ACTIVE,USER_NAME,USER_MAIL,DATE,TEXT) VALUES (NULL ,'$arrFields[BLOG_POST_ID]','$arrFields[ACTIVE]','$arrFields[USER_NAME]','$arrFields[USER_MAIL]','$arrFields[DATE]','$arrFields[TEXT]')");
		
		if(!$retval):
			die('<div class="error">Запись не смогла сама себя записать в базу.</div><br />' . mysql_error());
		else:
			return 'ok';
		endif;
	}
	
}

// mysql_close($_connect);

?>