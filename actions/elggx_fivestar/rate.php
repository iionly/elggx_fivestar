<?php

$guid = (int) get_input('id');
$vote = (int) get_input('vote');
$vote_input = $vote;
$rate = (int) get_input('rate_avg');

if (!$vote && $rate) {
	$vote = $rate;
}

$msg = elggx_fivestar_vote($guid, $vote);

// Get the new rating
$rating = elggx_fivestar_getRating($guid);

$rating['msg'] = $msg;

$output = '';
if (!$vote_input && $rate) {
	$output = elgg_echo('elggx_fivestar:rating_saved');
} else {
	$output = json_encode($rating);
}

return elgg_ok_response($output, '', REFERER);
