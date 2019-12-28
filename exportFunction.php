<?php
// メインqueryのファイルを読み込み
include_once(dirname(__file__) . '/action/export.php');

// チェックボックスのリストを作成
$itemArray = array(
	'商品名' => '商品名',
	'品番' => '品番',
	'上代' => '上代',
	'カートン入数' => '入数',
	'商品サイズ' => '本体サイズ',
	'重量' => '重量',
	'材質' => '材質',
	'パッケージ' => 'パッケージ',
	'パッケージサイズ' => 'パッケージサイズ',
	'製品情報' => '製品情報'
);
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
	<input type="submit" value="エクスポート" class="btn-export">
</form>

<?php

// exportAction
if( $_POST['action'] === "export" ) {
	$exportQuery = new Export($itemArray);
	$exportQuery->arraySet();
	$exportQuery->GetterPostDate();
}

?>
