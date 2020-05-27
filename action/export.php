<?php
include_once("itemRegistration.php");

class Export extends ItemRegistration {
	// const
	const ITEM_NAME = '商品名';
	const ITEM_NO = '品番';

	// @construct
	public function __construct($itemArray) {
		parent::__construct();

		include_once(dirname(__file__) . '/../Classess/PHPExcel.php');
		include_once(dirname(__file__) . '/../Classess/PHPExcel/IOFactory.php');

		$this->itemArray = $itemArray;
		$this->itemValue = array();
		$this->itemKey = array(self::ITEM_NAME, self::ITEM_NO);
		$this->posts = $this->db->prefix . "posts";
		$this->pmeta = $this->db->prefix . "postmeta";
	}

	// @method
	// @desc チェックリストの項目からチェックしているリストを配列化する
	// param {string} $itemArray - チェックリスト項目
	public function arraySet() {
		foreach($this->itemArray as $key => $value) {
			// チェックボックスで選択されているのかの判定を持たせる
			if(isset($_POST[$value])) {
				// 商品名と品番はdefで設定するため除外
				if ($key !== self::ITEM_NAME  && $key !== self::ITEM_NO) {
					Array_push($this->itemValue, $value);
					Array_push($this->itemKey, $key);
				}
			}
		}
	}

	// @method
	// @desc 商品の情報を取得し、カスタムフィールドへのアクセス設定
	public function GetterPostDate() {
		$rowIndex = 2;
		$titleCell = 0;
		$nomCell = 1;
		$deitalCell = 2;
		$itemDetaArray = array();
		$items = $this->db->get_results( $this->db->prepare(
			"select ID, post_title from $this->posts where post_type = 'item' and post_status = 'publish'", null)
		);

		// 項目名をセット
		for($i = 0; $i < count($this->itemKey); $i++) {
			$itemDetaArray = array_merge($itemDetaArray, array("{$this->alphaAscii($i)}1" => $this->itemKey[$i]));
		}

		// 取得項目を反映
		$itemTitles = array();
		foreach($items as $results) {
			$titleArea = $this->alphaAscii($titleCell) . $rowIndex;
			$numArea = $this->alphaAscii($nomCell) . $rowIndex;
			$itemDetaArray = array_merge($itemDetaArray, array($titleArea => $results->post_title));
			$itemDetaArray = array_merge($itemDetaArray, array($numArea => $this->getterItemDetails($results->ID, self::ITEM_NO)));
			for($i = 0; $i < count($this->itemValue); $i++) {
				$itemDetails = $this->alphaAscii(($deitalCell + $i)) . $rowIndex;
				if ($this->itemValue[$i] !== "manufacturer") {
					$itemDetaArray = array_merge($itemDetaArray, array($itemDetails => $this->getterItemDetails($results->ID, $this->itemValue[$i])));
				} else {
					// メーカー取得
					$itemDetaArray = array_merge($itemDetaArray, array($itemDetails => parent::manufacturerItems($results->ID)));
				}
			}
			$rowIndex++;
		}
		$this->turnOnDataExcel($itemDetaArray);
	}

	// @method
	// @desc カスタムフィールドで設定している値を取得
	// param {int} $items_id - 商品のID
	// params {string} $get_value - 取得するmeta_key
	private function getterItemDetails($items_id, $get_value) {
		$result = $this->db->get_results($this->db->prepare(
			"select meta_value from $this->pmeta where post_id = $items_id and meta_key = %s", $get_value, null)
		);
		foreach($result as $param) {
			return $param->meta_value;
		}
	}

	// @method
	// @desc アルファベットをインクリメントでカウントアップさせる
	// @param {int} $call - イテレートのインデックス
	private function alphaAscii($cell) {
		return chr(65 + $cell);
	}

	// @method
	// @desc excelファイルに書き出し
	public function turnOnDataExcel($insertDate) {
		$exportExcel = new PHPExcel();
		$exportExcel->setActiveSheetIndex(0);
		$exportSheet = $exportExcel->getActiveSheet();

		foreach($insertDate as $area => $value) {
			$exportSheet->setCellValue($area, $value);
		}

		// buffering
		ob_clean();
		ob_start();

		header('content-encoding: utf-8');
		header('content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('content-disposition: attachment;filename="output.xlsx"');
		header('cache-control: max-age=0');

		$exportwriter = PHPExcel_IOFactory::createWriter($exportExcel, 'Excel2007');
		$exportwriter->save("php://output");
		exit();
	}
}
?>
