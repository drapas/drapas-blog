<? require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php'); ?>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<?
	$item_id = isset($_REQUEST['item'])? $_REQUEST['item'] : '';
	
	###########################################################################
	#	Update post
	if (isset($_REQUEST['post_edit']) && $_REQUEST['post_edit'] == 'Y'):
	
		$_update_post = new Blog;
		
		$_date = isset($_REQUEST['post_date'])? $_REQUEST['post_date'] : date('Y-m-d');
		$_title = isset($_REQUEST['post_title'])? $_REQUEST['post_title'] : '';
		$_post_text = isset($_REQUEST['post_text'])? $_REQUEST['post_text'] : '';
		$_post_detail_text = isset($_REQUEST['post_detail_text'])? $_REQUEST['post_detail_text'] : '';
		$_post_tags = isset($_REQUEST['post_tags'])? $_REQUEST['post_tags'] : '';
		
		$arrFields = array(
			'ACTIVE' => 'Y',
			'TITLE' => $_title,
			'DATE' => $_date,
			'TEXT' => $_post_text,
			'DETAIL_TEXT' => $_post_detail_text,
			'TAGS' =>  $_post_tags
			);
		
		$_update_post_done = $_update_post->Update($item_id,$arrFields);
		
		if ($_update_post_done):
			header('Location: detail.php?item='.$item_id);
		endif;
	endif;
	
	$_post_detal = Blog::getByID($item_id);
	
	// echo '<pre>'; var_dump($_post_detal); echo '</pre>';
?>
			<div class="detail-block">
				<form action="">
					<div class="edit-title">
						Заглавие: <input class="edit-title-input" type="text" name="post_title" value="<?=$_post_detal['TITLE']?>" />
					</div>
					<div class="edit-date">
						дата: <input class="edit-date-input" type="text" name="post_date" value="<?=$_post_detal['DATE']?>" />
					</div>
					<div class="edit-text">
						Текст описания:<br />
						<textarea class="edit-text-input" name="post_text" id="" cols="30" rows="10"><?=$_post_detal['TEXT']?></textarea>
					</div>
					<div class="edit-detail-text">
						Детальный текст:<br />
						<textarea class="edit-dtext-input" name="post_detail_text" id="" cols="30" rows="10"><?=$_post_detal['DETAIL_TEXT']?></textarea>
					</div>
					<div class="edit-tags">
						теги: <input class="edit-tags-input" type="text" name="post_tags" value="<?=$_post_detal['TAGS']?>" /> (разделять через запятую)
					</div>
					<input type="hidden" name="post_author" value="<?=$_post_detal['USER']?>" />
					<input type="hidden" name="item" value="<?=$item_id?>" />
					<input type="hidden" name="post_edit" value="Y" />
					<input type="submit" value="Сохранить" />
					<? /*<a class="button fl-l" href="delete.php?item=<?=$item_id?>">удалить сообщение</a>*/ ?>
				</form>
			</div>
<? require_once($_SERVER["DOCUMENT_ROOT"].'/template/footer.php'); ?>