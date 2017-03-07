<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>

<?php
$list = function_exists('get_list') ? get_list() : G\get_global('list');
$tabs = (array) (G\get_global('tabs') ? G\get_global('tabs') : (function_exists('get_tabs') ? get_tabs() : NULL));

$classic = isset($_GET['pagination']) || CHV\getSetting('listing_pagination_mode') == 'classic';
$do_pagination = !isset($list->pagination) or $list->pagination == true ? true : false;
foreach($tabs as $tab) {
	if($tab['list'] === false) continue;
	if($tab["current"]) {
?>
<div id="<?php echo $tab["id"]; ?>" class="tabbed-content content-listing visible list-<?php echo $tab["type"]; ?>" data-action="list" data-list="<?php echo $tab["type"]; ?>" data-params="<?php echo $tab["params"]; ?>" data-params-hidden="<?php echo $tab["params_hidden"]; ?>">
	<?php
		if($list->output and count($list->output) > 0) {
	?>
	<div class="pad-content-listing"><?php echo $list->htmlOutput($list->output_tpl ? $list->output_tpl : NULL); ?></div>
	<?php
		if($do_pagination) {
	?>
	<div class="content-listing-more">
		<button class="btn btn-big grey" data-action="load-more"><?php _se('Load more'); ?></button>
	</div>
	<?php
	}

		if(count($list->output) >= $list->limit) {
	?>
	<div class="content-listing-loading"></div>
	<?php
		} 
		
		if($do_pagination and ($classic or count($list->output) >= $list->limit)) { // pagination
	?>
	<?php
		if($classic) {
			CHV\Render\show_banner('listing_before_pagination');
		}
	?>
	<ul class="content-listing-pagination<?php if($classic) { ?> visible<?php } ?>" data-visibility="<?php echo $classic ? 'visible' : 'hidden'; ?>" data-content="listing-pagination" data-type="<?php echo $classic ? 'classic' : 'endless'; ?>">
	<?php		
				$current_url = G\add_ending_slash(preg_replace('/\?.*/', '', G\get_current_url()));
				$current_url .= '?' . $tab["params"] . '&' . 'pagination';
				
				preg_match('/page=([0-9]+)/', $tab["params"], $matches);
				$current_page_qs = $matches[0];
				
				$page = intval($_GET['page'] ? $_GET['page'] : $matches[1]);
				$padding = 3;
				$offset = ['top' => abs($page - 1), 'bottom' => abs($list->totals['pages'] - $page)];
				
				$pages = ['first' => [], 'prev' => []];
				
				$start = 1;
				$end = $list->totals['pages'];
				
				if($list->totals['pages'] > ($padding*2 + 1)) {

					if($offset['top'] > $padding) {
						$start = $page - $padding;
					}
					if($offset['bottom'] > $padding) {
						$end = $page + $padding;
					}
					
					// must fill?
					$range = $end - $start;
					if($range < $padding*2) {
						$start = $end == $list->totals['pages'] ? $start - ($padding*2 - $range) : $start;
						$end = $start == 1 ? $end + ($padding*2 - $range) : $end;
					}

				}
				
				$range = $end - $start;
				
				for($i=$start; $i <= $end; $i++) {
					$pages[$i] = ['label' => $i, 'url' => str_replace($current_page_qs, 'page='.$i, $current_url)];
				}
				$pages[$page]['current'] = true;
				
				// first
				if(!array_key_exists(1, $pages)) {
					$pages['first'] = ['label' => '&laquo;', 'url' => str_replace($current_page_qs, 'page=1', $current_url)];
				} else {
					unset($pages['first']);
				}
				
				// previous
				if($page - 1 > 0) {
					$pages['prev'] = ['label' => '&lsaquo; ' . _s('Previous'), 'url' => str_replace($current_page_qs, 'page='.($page - 1), $current_url)];
				} else {
					unset($pages['prev']);
				}
				
				// next
				if($page < $list->totals['pages']) {
					$pages['next'] = ['label' => _s('Next') . ' &rsaquo;', 'url' => str_replace($current_page_qs, 'page='.($page + 1), $current_url), 'load-more' => !$classic ? true : false];
				}
				
				// last
				if(!array_key_exists($list->totals['pages'], $pages)) {
					$pages['last'] = ['label' => '&raquo;', 'url' => str_replace($current_page_qs, 'page='.$list->totals['pages'], $current_url)];
				}

				foreach($pages as $page) {
		?>
		<li><a <?php if($page['load-more']) { ?>data-action="load-more" <?php } ?>href="<?php echo $page['url']; ?>"<?php if($page['current']) {?> class="current"<?php } ?>><?php echo $page['label']; ?></a></li>
		<?php
				}
		?>
		<script>
			$(document).ready(function() {
				$("a[href]", "[data-content=listing-pagination]").each(function() {
					$(this).attr("href", $(this).attr("href").removeURLParameter("pagination"));
				});
			});
		</script>
	</ul>
	<?php
		if($classic) {
			CHV\Render\show_banner('listing_after_pagination');
		}
	?>
	<?php
			} // pagination?
		} else { // Results?
			G\Render\include_theme_file("snippets/template_content_empty");
		}
	?>
</div>
<?php
	} else { // !current
?>

<div id="<?php echo $tab["id"]; ?>" class="tabbed-content content-listing hidden list-<?php echo $tab["type"]; ?>" data-action="list" data-list="<?php echo $tab["type"]; ?>" data-params="<?php echo $tab["params"]; ?>" data-params-hidden="<?php echo $tab["params_hidden"]; ?>" data-load="<?php echo $classic ? 'classic' : 'ajax'; ?>">
</div>

<?php
	}
} // for
?>

<!--googleoff: index-->
<?php G\Render\include_theme_file("snippets/templates_content_listing"); ?>
<!--googleon: index-->