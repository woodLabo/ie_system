<?php
$user = wp_get_current_user();
if($user->id !== 1) {
	echo "権限により現在使用できません。";
} else {
?>

<form action="">
</form>
<?php } ?>
