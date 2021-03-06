<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2014-03-14 20:26:27
 * @@Modify Date: 2018-03-09 10:46:30
 * @@Function:
 */
?>
<?php
class Magiccart_Megashop_IndexController extends Mage_Core_Controller_Front_Action
{
    public function ajaxAction()
    {
    	if ($this->getRequest()->isAjax()) {

	        $this->loadLayout();    
	        $this->renderLayout();
	        return $this;
	    } else {
            $this->_redirectReferer();
        }
        
    }

    public function ajax2Action()
    {
        if ($this->getRequest()->isAjax()) {

            $this->loadLayout();    
            $this->renderLayout();
            return $this;
        } else {
            $this->_redirectReferer();
        }
        
    }

    public function productAction()
    {
        $this->loadLayout();   
        $type = $this->getRequest()->getParam('type');
        $title = $this->getProductTitle($type);
        $this->getLayout()->getBlock("head")->setTitle($this->__($title));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home"),
                "title" => $this->__("Home"),
                "link"  => Mage::getBaseUrl()
           ));
        $breadcrumbs->addCrumb("megashop", array(
                "label" => $this->__($title),
                "title" => $this->__($title)
           )); 
        $this->renderLayout();
    }

    public function getProductTitle($tp)
    {
        $types = Mage::getSingleton("megashop/system_config_type")->toOptionArray();
        foreach ($types as $type) {
            if($type['value'] == $tp) return $type['label'];
        }
    }

}

