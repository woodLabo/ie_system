<?php
// チェックボックスのリストを作成
$itemArray = array(
	'商品名' => 'item',
	'上代' => 'hoge',
	'品番' => 'num',
	'カートン入数' => 'cart',
	'商品サイズ' => 'size',
	'重量' => 'weigth',
	'材質' => 'parts',
	'パッケージ' => 'pack',
	'パッケージサイズ' => 'psize',
	'製品情報' => 'detail'
);
?>
<p>エクスポートする項目を選択してください</p>
<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" class="admin-export-form">
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
// メインのアクションを作成

?>
