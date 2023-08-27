<?php

/// @details 상품 클래스
class GoodClass {
	// Properties
	protected $CI;

	// 클래스 변수
	public $GOODS_ID = "";
	public $GOODS_NM = "";
	public $GOODS_SAP_CD = "";
	public $GTYPE_CD = "";
	public $GOODS_BRIF_TX = "";
	public $GOODS_TX = "";
	public $GOODS_USE_FL = "Y";
	public $GOODS_REG_DT = "";

	// 가격 정보
	public $GOODS_PRICE_ARR = array();

	// 상품 이미지
	public $GOODS_IMG_ARR = array();

	/// @brief Constructor
	public function __construct() {
		// Code to run when the class is instantiated
		$this->CI =& get_instance();

		// 날짜 생성일 기준 초기화
		$date = date('Y-m-d H:i:s');
		$this->GOODS_REG_DT = $date;
	}

	/// @brief 가격정보 추가
	/// @param	$unit	상품갯수
	/// @param	$before	원래가격
	/// @param	$sale	할인가격
	/// @return none
	public function setPriceARR($unit, $before, $sale)
	{
		for($i=0;$i<count($unit);$i++) {
			$tmp = new stdClass();
			$tmp->GPRICE_AMT = $unit[$i];
			$tmp->GPRICE_PC = $before[$i];
			$tmp->GPRICE_SALE_PC = $sale[$i];
			$tmp->GPRICE_ORD = $i;

			array_push($this->GOODS_PRICE_ARR, $tmp);
		}

	}

	/// @brief 상품이미지 추가
	/// @param	$sqidx	이미지 시퀀스
	/// @param	$fileName	파일이름
	/// @param	$order	정렬순서
	/// @return none
	public function addImageFile($sqidx, $fileName)
	{
		// 파일명 수정 (GOODS_ID+i)
		//$_tmp = explode(".", $fileName);
		//$_fileName = $this->GOODS_ID.$order.".".$_tmp[1];

		$tmp = $this->GOODS_IMG_ARR[$sqidx];
		$tmp->fileName = $fileName;
	}

	/// @brief 이미지 배열 생성
	/// @param	$sq	이미지sq 배열
	/// @param	$name	파일이름 배열
	/// @param	$repr	이미지 대표플래그 배열
	/// @return none
	public function setImageARR($sq, $name, $repr)
	{
		for($i=0;$i<count($repr);$i++) {
			$tmp = new stdClass();
			if(isset($sq[$i])) $tmp->sq = $sq[$i]; else $tmp->sq = "";
			if(isset($name[$i])) $tmp->fileName = $name[$i]; else $tmp->fileName = "";
			$tmp->order = $i;
			$tmp->represent = $repr[$i];
			array_push($this->GOODS_IMG_ARR, $tmp);
		}
	}

}
?>
