<?php

class ItemRegistration {
	// @construct
	function __construct() {
		$this->db = $GLOBALS['wpdb'];

		$this->terms = $this->db->prefix . "terms";
		$this->t_tax = $this->db->prefix . "term_taxonomy";
		$this->t_r = $this->db->prefix . "term_relationships";
		$this->termArray = array();
		$this->manufacturerTerm = array();
		$this->manufacturerTax = array();
	}

	// @method
	// @desc 投稿idからメーカーのタクソノミを取得
	// @params {int} $post_id - 投稿id
	public function manufacturerItems($post_id) {
		$tax_list = $this->db->get_results( $this->db->prepare(
			"select term_taxonomy_id from $this->t_r where object_id = %s", $post_id, null)
		);
		list($term, $tax) = $this->manufacturerRelation("manufacturer");
		foreach($tax_list as $tax) {
			if (in_array($tax->term_taxonomy_id, $term)) {
				return $this->manufacturerTaxName($tax->term_taxonomy_id);
			}
		}
	}

	// @method
	// @desc タクソノミー名からリレーションされているidの取得
	// @params {string} $tax_name - タクソノミー名
	private function manufacturerRelation($tax_name) {
		$names = $this->db->get_results( $this->db->prepare(
			"select term_taxonomy_id, term_id from $this->t_tax where taxonomy = %s", $tax_name, null)
		);
		foreach($names as $list) {
			array_push($this->manufacturerTerm, $list->term_id);
			array_push($this->manufacturerTax, $list->term_taxonomy_id);
		}
		return array($this->manufacturerTerm, $this->manufacturerTax);
	}

	// @method
	// @desc term_idからterm名を取得
	// @params {int} $term_id - term_id
	private function manufacturerTaxName($term_id) {
		return $this->db->get_var( $this->db->prepare(
			"select name from $this->terms where term_id = %s", $term_id, null)
		);
	}

	// @method
	// @desc チェックリストの配列
	public function manufacturerList() {
		return array(
			'商品名' => '商品名',
			'品番' => '品番',
			'上代' => '上代',
			'カートン入数' => '入数',
			'商品サイズ' => '本体サイズ',
			'重量' => '重量',
			'材質' => '材質',
			'パッケージ' => 'パッケージ',
			'パッケージサイズ' => 'パッケージサイズ',
			'製品情報' => '製品情報',
			'メーカー名' => 'manufacturer',
		);
	}
}
?>
