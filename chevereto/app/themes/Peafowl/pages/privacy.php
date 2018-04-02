<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>
<?php
// Some G\ magic, set the page doctitle from theme file
G\Handler::$vars['doctitle'] = 'Example page - ' . G\Handler::$vars['doctitle'];
?>
<?php G\Render\include_theme_header(); ?>

<div class="content-width">
	<div class="c24 center-box">
		<div class="header default-margin-bottom">
			<h1>Example page</h1>
		</div>
		<div class="text-content">
			<p>This is an example page for your Chevereto site. You can edit and learn from this file located in <span class="highlight"><?php echo G\absolute_to_relative(__FILE__); ?></span>. If you want a real world example you should check the <a href="<?php echo G\get_base_url('page/contact'); ?>">contact page</a> which is <code>contact.php</code> in the same folder.</p>
			<h2>Creating pages</h2>
			<p>To add a page like <code>mypage.php</code> simply add that file to the pages path. The system will auto bind this page to <code><?php echo G\get_base_url('page/mypage'); ?></code></p>
			<h2>Custom styles and coding</h2>
			<p>Chevereto pages are a wrapper of The <a href="http://gbackslash.com">G\ Library</a> loader which means that this pages can be complete customized in absolute all the code. You can use your own header, footer, metatags, etc.</p>
			<p>This means that you can use any code you want including HTML, JS, CSS and PHP. You can even create pages that look completely different from the main site look and you can even use all the system classes and functions (Both G\ and Chevereto) to make it easier and yet more powerful.</p>
			<h2>More help</h2>
			<p>If you need more help we suggest you to go to <a href="http://chevereto.com">Chevereto</a> support and read the <a href="http://gbackslash.com/docs">G\ Library documentation</a>. View the code of this file will also help you to understand the magic behind this system.</p>
		</div>
	</div>
</div>

<?php G\Render\include_theme_footer(); ?>