<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>
<?php G\Render\include_theme_header(); ?>

<?php
	if(get_user()["background"] or is_owner() or is_admin()) {
?>
<div id="background-cover" data-content="user-background-cover"<?php if(!get_user()["background"]) { ?> class="no-background"<?php } ?>>
	<div id="background-cover-wrap">
		<div id="background-cover-src" data-content="user-background-cover-src"<?php if(get_user()["background"]["url"]) { ?> style="background-image: url('<?php echo get_user()["background"]["url"]; ?>');"<?php } ?>></div>
	</div>
	<div class="content-width">
		<?php
        	if(is_owner() or is_admin()) {
		?>
		<span data-content="user-upload-background" class="btn btn-input default<?php if(get_user()["background"]) { ?> hidden<?php } ?>" data-trigger="user-background-upload"><?php _se('Upload profile background'); ?></span>
		<div id="change-background-cover" data-content="user-change-background" class="pop-btn<?php if(!get_user()["background"]) { ?> hidden<?php } ?>">
			<span class="btn btn-capsule"><span class="btn-icon icon-camera"></span><span class="btn-text"><?php _se('Change background'); ?></span></span>
			<div class="pop-box anchor-right arrow-box arrow-box-top">
				<div class="pop-box-inner pop-box-menu">
					<ul>
						<li><a data-trigger="user-background-upload"><?php _se('Upload new image'); ?></a></li>
						<li><a data-confirm="<?php _se("The profile background image will be deleted. This can't be undone. Are you sure that you want to delete the profile background image?"); ?>" data-submit-fn="CHV.fn.user_background.delete.submit" data-ajax-deferred="CHV.fn.user_background.delete.deferred"><?php _se('Delete background'); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<input id="user-background-upload" data-content="user-background-upload-input" class="hidden-visibility" type="file" accept="image/*">
		<?php
        	}
		?>
	</div>
	<div class="loading-placeholder hidden"></div>
</div>
<?php
	}
?>

<div class="content-width">
	
	<?php CHV\Render\show_banner('user_after_top'); ?>
	
	<div id="top-user" class="top-user<?php echo (!get_user()["background"] and (!is_owner() and !is_admin())) ? ' user-has-no-background' : NULL; ?>">
		<div class="top-user-credentials">
			<a href="<?php echo get_user()["url"]; ?>">
				<?php
					if(get_user()["avatar"]) {
				?>
				<img class="user-image" src="<?php echo get_user()["avatar"]["url"]; ?>" alt="">
				<?php
					} else {
				?>
				<span class="user-image default-user-image"><span class="icon icon-user"></span></span>
				<?php
					}
				?>
			</a>
			<h1><a href="<?php echo get_user()["url"]; ?>"><?php echo get_safe_html_user()[get_user()["name"] ? "name" : "username"]; ?></a></h1>
			<div class="user-meta user-social-networks"><a href="<?php echo get_user()["url"]; ?>"><?php echo get_user()["username"]; ?></a> <?php if(get_user()["twitter"]) { ?><a class="icon-twitter" href="<?php echo get_user()["twitter"]["url"]; ?>" rel="nofollow" target="_blank"></a><?php } if(get_user()["facebook"]) { ?><a class="icon-facebook" href="<?php echo get_user()["facebook"]["url"]; ?>" rel="nofollow" target="_blank"></a><?php } if(get_user()["website"]) { ?><a class="icon-globe" href="<?php echo get_user()["website"]; ?>" rel="nofollow" target="_blank"></a><?php } ?></div>
			
			<?php
				if(is_owner() or is_admin()) {
			?>
			<div class="user-meta">
				<a class="edit-link" href="<?php echo G\get_base_url(is_owner() ? 'settings/profile' : 'dashboard/user/' . get_user()['id']); ?>"><span class="icon-edit"></span><span><?php _se('Edit profile'); ?></span></a>
			<?php
					if(!is_owner() and is_admin()) {
			?>
				<a class="delete-link margin-left-5" data-confirm="<?php _se("Do you really want to delete this user? This can't be undone."); ?>" data-submit-fn="CHV.fn.submit_resource_delete" data-ajax-deferred="CHV.fn.complete_resource_delete" data-ajax-url="<?php echo G\get_base_url("json"); ?>"><?php _se('Delete user'); ?></a>
			<?php
					}
			?>
			</div>
			<?php
				}
			?>
			
			<?php if(get_user()['bio']) { ?>
			<div class="user-meta overflow-hidden">
				<p class="c18 word-break-break-word"><?php echo G\linkify_safe(get_user()['bio']); ?></p>
			</div>
			<?php } ?>
			
		</div>
		
		<div class="header-content-right phone-float-none">
			<div class="float-right phone-float-none">
				<a class="number-figures float-left phone-float-none" href="<?php echo get_user()["url"]; ?>"><b data-text="image-count"><?php echo get_user()["image_count"]; ?></b> <span data-text="image-label" data-label-single="<?php _ne('image', 'images', 1); ?>" data-label-plural="<?php _ne('image', 'images', 2); ?>"><?php _ne('image', 'images', get_user()['image_count']); ?></span></a><a class="number-figures float-left phone-float-none" href="<?php echo get_user()["url_albums"]; ?>"><b data-text="album-count"><?php echo get_user()["album_count"]; ?></b> <span data-text="album-label" data-label-single="<?php _ne('album', 'albums', 1); ?>" data-label-plural="<?php _ne('album', 'albums', 2); ?>"><?php _ne('album', 'albums', get_user()['album_count']); ?></span></a>
			</div>
			<div class="input-search float-left phone-float-none">
				<form action="<?php echo get_user()["url"] . "/search"; ?>">
					<input class="search one-icon-padding" type="text" placeholder="<?php echo get_safe_html_user()["name"]; ?>" autocomplete="off" spellcheck="false" name="q">
				</form>
				<span class="icon-search"></span><span class="icon close icon-close soft-hidden" data-action="clear-search"></span>
			</div>
		</div>
	</div>
	
	<?php
		if(get_user()["background"] or is_owner() or is_admin()) {
			CHV\Render\show_theme_inline_code('snippets/user.js');
		}
	?>
	
	<?php CHV\Render\show_banner('user_before_listing'); ?>
	
	<div class="header header-tabs margin-bottom-10 follow-scroll">
		<?php
			if(is_user_search()) {
		?>
		<div class="heading display-inline-block">
			<span class="phone-hide"><?php _se('Results for'); ?></span>
			<h1 class="display-inline"><strong><?php echo get_safe_html_user()["search"]["d"]; ?></strong></h1>
		</div>
		<?php
			} else {
		?>
		<a href="<?php echo get_user()["url"]; ?>" class="user-image margin-right-10 float-left hidden" data-show-on="follow-scroll">
			<?php if(get_user()["avatar"]) { ?>
			<img src="<?php echo get_user()["avatar"]["url"]; ?>" alt="">
			<?php } else { ?>
			<span class="user-image default-user-image"><span class="icon icon-user"></span></span>
			<?php } ?>
		</a>
		<h1 class="phone-hide"><?php echo sprintf(is_user_images() ? _s("%s's Images") : _s("%s's Albums"), get_user()["firstname_html"]); ?></h1>
		<h1 class="phone-show hidden"><?php echo is_user_images() ? _s("Images") : _s("Albums"); ?></h1>
		<?php
			}
		?>
        
        <?php G\Render\include_theme_file("snippets/tabs"); ?>
        
		<?php
			if(is_owner() or is_admin()) {
				G\Render\include_theme_file("snippets/user_items_editor");
		?>
        <div class="header-content-right phone-float-none">
			<?php G\Render\include_theme_file("snippets/listing_tools_editor"); ?>
        </div>
		<?php
			}
		?>

    </div>
	
	<div id="content-listing-tabs" class="tabbed-listing">
        <div id="tabbed-content-group">
            <?php
                G\Render\include_theme_file("snippets/listing");
            ?>
        </div>
    </div>
	
</div>

<?php G\Render\include_theme_footer(); ?>

<?php if((is_owner() or is_admin()) and isset($_REQUEST["deleted"])) { ?>
<script>PF.fn.growl.expirable("<?php _se('The content has been deleted.'); ?>");</script>
<?php } ?>