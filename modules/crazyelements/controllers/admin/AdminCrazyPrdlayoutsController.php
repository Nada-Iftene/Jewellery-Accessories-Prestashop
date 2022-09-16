<?php

require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
use CrazyElements\PrestaHelper;

class AdminCrazyPrdlayoutsController extends ModuleAdminController {


	public function initContent() {
		return parent::initContent();
	}
    public function renderList() {
		$html = "";
        $html.='<div class="panel col-lg-12">
        <div class="font-prev-wrapper" style="text-align: center;">
        <h2>You Need The Pro Version to Edit Product Layout. <a style="color: blue;" href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_prdlayouts&utm_medium=crazyfree_prdlayouts_module&utm_campaign=crazyfree_prdlayouts&utm_term=crazyfree_prdlayouts&utm_content=crazyfree_prdlayouts?utm_source=crazyfree_prdlayouts&utm_medium=crazyfree_prdlayouts_module&utm_campaign=crazyfree_prdlayouts&utm_term=crazyfree_prdlayouts&utm_content=crazyfree" target="_blank">Get PRO</a></h2><br>
        <a  href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_prdlayouts&utm_medium=crazyfree_prdlayouts_module&utm_campaign=crazyfree_prdlayouts&utm_term=crazyfree_prdlayouts&utm_content=crazyfree_prdlayouts?utm_source=crazyfree_prdlayouts&utm_medium=crazyfree_prdlayouts_module&utm_campaign=crazyfree_prdlayouts&utm_term=crazyfree_prdlayouts&utm_content=crazyfree" target="_blank"> <img src=" ' . CRAZY_ASSETS_URL . 'images/price_compare.png" width="1200"></a></div></div>';
        return $html;
	}
}