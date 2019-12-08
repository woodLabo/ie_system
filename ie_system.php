<?php
/*
	Plugin Name: import export filer
	Description: 登録商品のインポート・エクスポートに使用してください。
	Author: woodsLabo
	Version: 1
 */
?>
<?php
class SystemInt {
	public function __construct() {
		add_action('admin_menu', array($this, 'add_system'));
		add_action('admin_enqueue_scripts', array($this,'add_style'));
		add_action('admin_enqueue_scripts', array($this, 'add_script'));
	}

	public function add_system() {
		add_menu_page('商品登録', '商品登録', 'manage_options', 'loader_page', array($this, 'load_system'), '', 30);
		add_submenu_page('loader_page', 'インポート', 'インポート', 'manage_options', 'import_page', array($this, 'import_system'));
		add_submenu_page('loader_page', 'エクスポート', 'エクスポート', 'manage_options', 'export_page', array($this, 'export_system'));
		remove_submenu_page('loader_page', 'loader_page');
	}

	public function import_system() {
	?>
	<h2>商品インポート</h2>
	<p>インポートするファイルを選択してください</p>
	<?php
		require_once('importFunction.php');
	}

	public function export_system() {
	?>
	<h2>商品エクスポート</h2>
	<?php
		require_once('exportFunction.php');
	}

	public function add_style() {
		wp_enqueue_style('add_style', plugin_dir_url(__FILE__) . '/asset/css/style.css');
	}

	public function add_script() {
		wp_enqueue_style('add_script', plugin_dir_url(__FILE__) . '/asset/css/main.js');
	}
}

$Addsystem = new SystemInt;

?>
