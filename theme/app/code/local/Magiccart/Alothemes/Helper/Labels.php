<?php
/**
 * Magiccart
 * @category 	 Magiccart
 * @copyright 	Copyright (c) 2014 ALO (http://www.magiccart.net/) 
 * @license 	http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2014-06-05 20:29:22
 * @@Modify Date: 2019-01-10 14:17:19
 * @@Function:
 */
 ?>
<?php
class Magiccart_Alothemes_Helper_Labels extends Mage_Core_Helper_Abstract
{	
    const SECTIONS      = 'alothemes';      // module name
    const GROUPS_LABELS   = 'labels';
	protected $labels = false;
	protected $new = 'New';
	protected $sale = 'Sale';

    public function getLabelsCfg($cfg=null)
    {
        $config =  Mage::getStoreConfig(self::SECTIONS .'/'.self::GROUPS_LABELS);
        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
    }

	public function getLabels($product)
	{
		$html  = '';
		if(!$this->labels) $this->labels = $this->getLabelsCfg();
		$config = $this->labels;

		$showNew = isset($config['newLabel']) ? $config['newLabel'] : true; // get in Cfg;
		if($showNew){
			$isNew = $this->isNew($product);
			if($isNew){
				$label = isset($config['newText']) ? $config['newText'] : $this->new;
				$html .= '<span class="sticker top-left"><span class="labelnew">' . $this->__($label) . '</span></span>';			
			}	
		}
		$showSale = isset($config['saleLabel']) ? $config['saleLabel'] : true; // get in Cfg;
		if($showSale){
			$isSale = $this->isOnSale($product);
			if($isSale){
				$percent = isset($config['salePercent']) ? $config['salePercent'] : false; // get in Cfg;
				if($percent){
					$price = $product->getPrice();
					$finalPrice = $product->getFinalPrice();
					$label = (int) $price ? floor(($finalPrice/$price)*100 - 100).'%' : '';
				}else {
					$label = isset($config['saleText']) ? $config['saleText'] : $this->sale;
				}
				$html .= '<span class="sticker top-right"><span class="labelsale">' . $this->__($label) . '</span></span>';
			}
		}
		
		return $html;
	}

	public function isNew($product)
	{
		return $this->_nowIsBetween($product->getData('news_from_date'), $product->getData('news_to_date'));
	}

	public function isOnSale($product)
	{
		$specialPrice = number_format($product->getFinalPrice(), 2);
		$regularPrice = number_format($product->getPrice(), 2);
		
		if ($specialPrice != $regularPrice)
			return $this->_nowIsBetween($product->getData('special_from_date'), $product->getData('special_to_date'));
		else
			return false;
	}
	
	protected function _nowIsBetween($fromDate, $toDate)
	{
		if ($fromDate)
		{
			$fromDate = strtotime($fromDate);
			$toDate = strtotime($toDate);
			$now = strtotime(date("Y-m-d H:i:s"));
			
			if ($toDate)
			{
				if ($fromDate <= $now && $now <= $toDate)
					return true;
			}
			else
			{
				if ($fromDate <= $now)
					return true;
			}
		} else if($toDate) {
			$toDate = strtotime($toDate);
			$now = strtotime(date("Y-m-d H:i:s"));
			if ($now <= $toDate)
				return true;
		}
		
		return false;
	}

    public function getImageHover($product)
    {
	    return  $product->load('media_gallery')
	                    ->getMediaGalleryImages()
	                    ->getItemByColumnValue('position','2') //->getItemByColumnValue('label','Imagehover')
	                    ->getFile();
    }

    public function getSoldQty($product)
    {

    	$productId = $product->getId();
    	$from 	= $product->getData('special_from_date');
    	$to  	= $product->getData('special_to_date');
    	// $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $SoldProducts = Mage::getResourceModel('reports/product_collection');
        // $SoldProdudctCOl = $SoldProducts->addOrderedQty()
        $SoldProdudctCOl = $SoldProducts->addOrderedQty($from, $to)
        							->addAttributeToFilter('entity_id', $productId);

        $sold = 0;
	        if($SoldProdudctCOl->count()){
	        foreach ($SoldProdudctCOl as $item) {
	        	$sold += (int) $item->ordered_qty;
	        }
        }
        // echo $SoldProdudctCOl->getSelect()->__toString(); // echo sql

        return $sold;
    }

}
