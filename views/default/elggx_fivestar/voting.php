<?php

elgg_require_js('elggx_fivestar/elggx_fivestar_voting');

$guid = isset($vars['fivestar_guid']) ? $vars['fivestar_guid'] : $vars['entity']->guid;

if (!$guid) {
	return;
}

$rating = elggx_fivestar_getRating($guid);
$stars = (int)elgg_get_plugin_setting('stars', 'elggx_fivestar');

$pps = 100 / $stars;

$checked = '';

$disabled = '';
if (!elgg_is_logged_in()) {
	$disabled = 'disabled="disabled"';
}

if (!(int)elgg_get_plugin_setting('change_cancel', 'elggx_fivestar')) {
	if (elggx_fivestar_hasVoted($guid)) {
		$disabled = 'disabled="disabled"';
	}
}

$unique_id = md5(uniqid(rand(), true));

$subclass = $vars['subclass'] ? ' ' . $vars['subclass'] : '';
$outerId = $vars['outerId'] ? 'id="' . $vars['outerId'] . '"' : '';
$ratingText = $vars['ratingTextClass'] ? 'class="' . $vars['ratingTextClass'] . '"' : '';
?>

<div <?php echo $outerId; ?> class="fivestar-ratings <?php echo $subclass;?>" data-guid="<?php echo $guid;?>" data-uniqueid="<?php echo $unique_id;?>">
	<form id="fivestar-form-<?php echo $guid."-".$unique_id; ?>" style="width: 200px" action="<?php echo elgg_get_site_url(); ?>action/elggx_fivestar/rate" method="post">
		<?php for ($i = 1; $i <= $stars; $i++) { ?>
			<?php if (round($rating['rating']) == $i) { $checked = 'checked="checked"'; } ?>
				<input type="radio" name="rate_avg" <?php echo $checked; ?> <?php echo $disabled; ?> value="<?php echo $pps * $i; ?>" />
				<?php $checked = ''; ?>
		<?php }
			echo elgg_view('input/hidden', array('name' => 'id','value' => $guid));
			echo elgg_view('input/securitytoken');
		?>
		<input type="submit" value="Rate it!" />
	</form>
	<div class="clearfloat">
	<?php if (!$vars['min']) { ?>
		<p <?php echo $ratingText; ?>>
			<span id="fivestar-rating-<?php echo $guid."-".$unique_id; ?>"><?php echo $rating['rating']; ?></span>/<?php echo $stars . ' ' . elgg_echo('elggx_fivestar:lowerstars'); ?> (<span id="fivestar-votes-<?php echo $guid."-".$unique_id; ?>"><?php echo $rating['votes'] . ' ' . elgg_echo('elggx_fivestar:votes'); ?></span>)
		</p>
	<?php } ?>
	</div>
</div>
