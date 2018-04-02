<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>
<?php
global $embed_tpl;
$embed_tpl = [
	'links' => [
		'label' => _s('Links'),
		'options' => [
			'viewer-links' => [
				'label'		=> _s('Viewer links'),
				'template'	=> '%URL_VIEWER%',
				'size'		=> 'viewer'
			],
			'direct-links'	=> [
				'label'		=> _s('Direct links'),
				'template'	=> '%URL%',
				'size'		=> 'full'
			]
		]
	],
	'html-codes' => [
		'label' => _s('HTML Codes'),
		'options' => [
			'html-embed'	=> [
				'label'		=> _s('HTML image'),
				'template'	=> '<img src="%URL%" border="0">',
				'size'		=> 'full'
			],
			'html-embed-full'	=> [
				'label'		=> _s('HTML full linked'),
				'template'	=> '<a href="%URL_VIEWER%"><img src="%URL%" border="0"></a>',
				'size'		=> 'full'
			],
			'html-embed-medium'		=> [
				'label'		=> _s('HTML medium linked'),
				'template'	=> '<a href="%URL_VIEWER%"><img src="%MEDIUM_URL%" border="0"></a>',
				'size'		=> 'medium'
			],
			'html-embed-thumbnail'	=> [
				'label'		=> _s('HTML thumbnail linked'),
				'template'	=> '<a href="%URL_VIEWER%"><img src="%THUMB_URL%" border="0"></a>',
				'size'		=> 'thumb'
			]
		]
	],
	'bbcodes' => [
		'label' => _s('BBCodes'),
		'options' => [
			'bbcode-embed'	=> [
				'label'		=> _s('BBCode full'),
				'template'	=> '[img]%URL%[/img]',
				'size'		=> 'full'
			],
			'bbcode-embed-full' => [
				'label'		=> _s('BBCode full linked'),
				'template'	=> '[url=%URL_VIEWER%][img]%URL%[/img][/url]',
				'size'		=> 'full'
			],
			'bbcode-embed-medium'	=> [
				'label'		=> _s('BBCode medium linked'),
				'template'	=> '[url=%URL_VIEWER%][img]%MEDIUM_URL%[/img][/url]',
				'size'		=> 'medium'
			],
			'bbcode-embed-thumbnail' => [
				'label'		=> _s('BBCode thumbnail linked'),
				'template'	=> '[url=%URL_VIEWER%][img]%THUMB_URL%[/img][/url]',
				'size'		=> 'thumb'
			]
		]
	]
];
?>
<script>
	$(document).ready(function() {
		if(typeof CHV == "undefined") {
			CHV = {obj: {}, fn: {}, str:{}};
		} else {
			if(typeof CHV.obj.embed_tpl == "undefined") {
				CHV.obj.embed_tpl = {};
			}
		}
		CHV.obj.embed_tpl = <?php echo json_encode($embed_tpl); ?>;
	});
</script>
<div data-modal="form-embed-codes" class="hidden">
	<span class="modal-box-title"><?php _se('Embed codes'); ?></span>
	<div class="input-label margin-bottom-0">
		<div class="c7 margin-bottom-10">
			<select name="from-embed-toggle" id="from-embed-toggle" class="text-input" data-combo="from-embed-toggle-combo">
				<?php
					foreach(G\get_global('embed_tpl') as $key => $value) {
						echo '<optgroup label="'.$value['label'].'">'."\n";
						foreach($value['options'] as $k => $v) {
							echo '	<option value="'.$k.'" data-size="'.$v["size"].'">'.$v["label"].'</option>'."\n";
						}
						echo '</optgroup>';
					}
				?>
			</select>
		</div>
		<div id="from-embed-toggle-combo">
			<?php
				$i=0;
				foreach(G\get_global('embed_tpl') as $key => $value) {
					foreach($value['options'] as $k => $v) {
						echo '<div data-combo-value="'.$k.'" class="switch-combo'.($i>0 ? " soft-hidden" : "").'">
							<textarea class="r3" name="'.$k.'" data-size="'.$v["size"].'"></textarea>
						</div>'."\n";
						$i++;
					}
				}
			?>
		</div>
	</div>
</div>