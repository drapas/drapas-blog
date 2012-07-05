<? require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php'); ?>
<?

	$item_id = isset($_REQUEST['item'])? $_REQUEST['item'] : '';

	###########################################################################
	#	Delete post
	if (isset($_REQUEST['post_delete']) && $_REQUEST['post_delete'] == 'Y'):
	
		$_delete_post = new Blog;
		$_delete_post_done = $_delete_post->Delete($item_id);
		
		if ($_delete_post_done):
			header('Location: index.php');
		endif;
	endif;
	
	if (isset($_REQUEST['comment_add']) && $_REQUEST['comment_add'] == 'Y'):
	
		$_new_comment = new Comments;
		
		$_comment_name = isset($_REQUEST['comment_name'])? $_REQUEST['comment_name'] : '';
		$_comment_mail = isset($_REQUEST['comment_mail'])? $_REQUEST['comment_mail'] : '';
		$_comment_text = isset($_REQUEST['comment_text'])? $_REQUEST['comment_text'] : '';
		$_comment_post_id = isset($_REQUEST['blog_post_id'])? $_REQUEST['blog_post_id'] : $item_id;
		
		$arrFields = array(
			'ACTIVE' => 'Y',
			'BLOG_POST_ID' => $_comment_post_id,
			'USER_NAME' => $_comment_name,
			'USER_MAIL' => $_comment_mail,
			'TEXT' => $_comment_text
			);
		
		$_add_comm_done = $_new_comment->Add($arrFields);
		
		echo $_add_comm_done;
		
		if ($_add_comm_done):
			header('Location: detail.php?item='.$item_id);
		endif;
	
	endif;
	
	$_post_detal = Blog::getByID($item_id);
	
	$_post_comments_list = Comments::getLIst($item_id);
	
	// echo '<pre>'; var_dump($_post_detal); echo '</pre>';
?>
			<div class="detail-block">
				<h2><?=$_post_detal['TITLE']?></h2>
				<div class="date"><?=$_post_detal['DATE']?></div>
				<div class="text"><?=$_post_detal['DETAIL_TEXT']?></div>
				<div class="author">автор: <strong><?=$_post_detal['USER']?></strong></div>
				<?
				$_tags = explode(',',$_post_detal['TAGS']);
				$_tags_count = sizeof($_tags);
				if ($_tags_count > 0):
				?>
				<div class="tags">теги: <?
				foreach($_tags as $key => $_tag):?>
					<a href="tags.php?tag=<?=$_tag?>"><?=$_tag?></a><? if ($key + 1 < $_tags_count) echo ','; ?>
				<?endforeach;?>
				</div>
				<? endif; ?>
			</div>
			<div class="edit-block">
				<a class="button fl-l" href="edit.php?item=<?=$item_id?>">редактировать</a>
				<a class="button fl-l" href="detail.php?post_delete=Y&item=<?=$item_id?>">удалить сообщение</a>
			</div>
			<div class="clear"></div>
			<? if (sizeof($_post_comments_list) > 0): ?>
			<div class="comments-block">
				<h2>Комментарии</h2>
				<ol>
				<? foreach($_post_comments_list as $value):?>
				<li>
					<div class="comment-block">
						<div class="name">автор: <a href="mailto:<?=$value['USER_MAIL']?>"><?=$value['USER_NAME']?></a> (<?=$value['DATE']?>)</div>
						<div class="comment"><?=$value['TEXT']?></div>
					</div>
				</li>
				<? endforeach; ?>
				</ol>
			</div>
			<? endif; ?>
			<div class="add-comment-block">
				<h2>Добавить комментарий</h2>
				<form action="">
					<input type="hidden" name="blog_post_id" value="<?=$item_id?>" />
					<input type="hidden" name="item" value="<?=$item_id?>" />
					<input type="hidden" name="comment_add" value="Y" />
					Имя:<br />
					<input type="text" name="comment_name" class="name"></input><br />
					email:<br />
					<input type="text" name="comment_mail" class="email"></input><br />
					Сообщение<br />
					<textarea name="comment_text" id="" cols="30" rows="10"></textarea>
					<input type="submit" value="Отправить камент" />
				</form>
			</div>
			
<? require_once($_SERVER["DOCUMENT_ROOT"].'/template/footer.php'); ?>