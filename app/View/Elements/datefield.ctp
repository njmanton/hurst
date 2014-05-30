<?php 

// get the localtime by subtracting tz 
$localtime = $time->sub(new DateInterval('PT' . abs($tz) . 'H'))->format(TF);

// get the user's time zone from settings
$time->add(new DateInterval('PT' . abs($tz) . 'H'));
if ($usertz > 0) {
	$usertime = $time->add(new DateInterval('PT' . $usertz . 'H'))->format(TF);
} else {
	$usertime = $time->sub(new DateInterval('PT' . abs($usertz) . 'H'))->format(TF);
}

$tip = __('Local Time: %s<br>User Time: %s', $localtime, $usertime);
?>
<?php if (!$timeonly) echo $time->format('d M') . ' '; ?>
<span data-tooltip class="has-tip tip-bottom radius" title="<?php echo $tip; ?>">
	<?php echo (isset($usertz)) ? $usertime : $localtime ; ?>
</span>