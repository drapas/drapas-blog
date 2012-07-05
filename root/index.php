<? require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php'); ?>

<? $arrBlog = Blog::getList(); ?>
		<ul>
		<?foreach($arrBlog as $post_content):
		// echo '<pre>'; var_dump($post_content); echo '</pre>';
		?>
			<li id="post-<?=$post_content['ID']?>" class="content-block">
				<h2><?=$post_content['TITLE']?></h2>
				<div class="date"><?=$post_content['DATE']?></div>
				<div class="text"><?=$post_content['TEXT']?></div>
				<div class="detail_text_link"><a href="detail.php?item=<?=$post_content['ID']?>">подробнее ..</a></div>
				<div class="author">автор: <strong><?=$post_content['USER']?></strong></div>
				<?$_tags = explode(',',$post_content['TAGS']);?>
				<div class="tags">теги: <?
				$_tags_count = sizeof($_tags);
				foreach($_tags as $key => $_tag):?>
					<a href="tags.php?tag=<?=$_tag?>"><?=$_tag?></a><? if ($key + 1 < $_tags_count) echo ','; ?>
				<?endforeach;?>
				</div>
			</li>
		<?endforeach;?>
		</ul>

<? require_once($_SERVER["DOCUMENT_ROOT"].'/template/footer.php'); ?>