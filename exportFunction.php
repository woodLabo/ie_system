<?php
// メインqueryのファイルを読み込み
include_once(dirname(__file__) . '/action/export.php');
include_once(dirname(__file__) . '/action/itemRegistration.php');

// exportで使用するitemの情報
$registration = new itemRegistration();
$itemArray = $registration->manufacturerList();
?>

<p>エクスポートする項目を選択してください</p>
<form action="<?php echo str_replace( '%7e', '~', $_server['request_uri']); ?>" method="post" class="admin-export-form">
	<input type="hidden" name="action" value="export">

<?php foreach($itemArray as $key => $value) {
	if ($key === "商品名" || $key === '品番') {
?>
	<input type="checkbox" value="<?php echo $value; ?>" name="<?php echo $value; ?>" id="<?php echo $value; ?>" checked="checked" readonly="readonly"><label for="<?php echo $value; ?>"><?php echo $key; ?></label>
<?php
	} else {
?>
	<input type="checkbox" value="<?php echo $value; ?>" name="<?php echo $value; ?>" id="<?php echo $value; ?>"><label for="<?php echo $value; ?>"><?php echo $key; ?></label>
<?php
	}
}
?>
<?php
$user = wp_get_current_user();
if($user->id !== 1) {
	echo "<br>";
	echo "権限により現在使用できません。";
} else {
?>
	<input type="submit" value="エクスポート" class="btn-export">
<?php } ?>
</form>

<?php

// exportAction
if( $_POST['action'] === "export" ) {
	$exportQuery = new Export($itemArray);
	$exportQuery->arraySet();
	$exportQuery->GetterPostDate();
}

?>
