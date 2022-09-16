<?php

/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
class SampleBlocks {

    public function initData($base_url) {

        $content_block1 = '<div class="ht-offer-section top-margin bottom-margin">
    <div class="inner-wrapper">
        <div class="container">
            <div class="row">
                <div class="img-block left-block">
                    <div class="img-block-inner"><a href="#"> <img src="img/cms/offer-img-1.jpg" alt="offer-img-1" /> </a>
                        <div class="text-content"><span>New Arrivals</span> <strong>Women <br />Collections</strong></div>
                    </div>
                </div>
                <div class="img-block right-block">
                    <div class="img-block-inner"><a href="#"> <img src="img/cms/offer-img-2.jpg" alt="offer-img-2" /> </a>
                        <div class="text-content"><span>New Arrivals</span> <strong>Man <br />Collections</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
        $content_block2 = '<div id="testimonial-section" class="testimonial-section top-margin bottom-margin">
    <div class="testimonial-wrapper">
        <div class="testimonial-inner ">
            <div class="container">
                <div class="section-title">
                    <h2>What client Says</h2>
                </div>
                <div class="row">
                    <div class="owl-carousel owl-theme testimonial-slider">
                        <div class="testimonial-content">
                            <div class="inner-content">
                                <div class="testimonial-detail"><img src="img/cms/testimonial-img-1.jpg" alt="testimonial1" />
                                    <div class="profile">
                                        <h4>Alex Carter</h4>
                                        <p>Web Developer</p>
                                    </div>
                                </div>
                                <div class="testimonial-desc">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="inner-content">
                                <div class="testimonial-detail"><img src="img/cms/testimonial-img-2.jpg" alt="testimonial2" />
                                    <div class="profile">
                                        <h4>Alina Joy</h4>
                                        <p>Customer</p>
                                    </div>
                                </div>
                                <div class="testimonial-desc">
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which slightly believable.</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="inner-content">
                                <div class="testimonial-detail"><img src="img/cms/testimonial-img-3.jpg" alt="testimonial3" />
                                    <div class="profile">
                                        <h4>John Doe</h4>
                                        <p>Web Designer</p>
                                    </div>
                                </div>
                                <div class="testimonial-desc">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
        $content_block3 = '<div class="contactinfo-section col-md-3 links wrapper">
    <div class="footer-links-column">
        <div class="footer-colum-title hidden-sm-down"><img src="img/cms/footer-logo.png" alt="logo" /></div>
        <div class="title clearfix hidden-md-up" data-target="#footer_contact_hours" data-toggle="collapse">
            <span class="h3">Quick Contact</span> 
            <span class="float-xs-right"> 
                <span class="navbar-toggler collapse-icons"> 
                    <i class="material-icons add">&#xE313;</i> 
                    <i class="material-icons remove">&#xE316;</i> 
                </span> 
            </span>
        </div>
        <div class="footer-column-content column-content collapse" id="footer_contact_hours">
            <div class="content">
                <div class="item"><span class="text-span">15 Main Rd E. St Albans VIC 1234, Australia</span></div>
                <div class="item"><a class="text-span" href="mailto:info@domain.com">Email: info@domain.com </a></div>
                <div class="item"><a class="text-span" href="tel:+1234-456-7890">Phone: +1 234-456-7890 </a></div>
            </div>
        </div>
    </div>
</div>
';

        $displayHome = Hook::getIdByName('displayHome');
        $displayFooter = Hook::getIdByName('displayFooter');
        $displayNav1 = Hook::getIdByName('displayNav1');
        $displayHomeTop1 = Hook::getIdByName('displayHomeTop1');
        $displayHomeTop2 = Hook::getIdByName('displayHomeTop2');
        $displayHomeTop3 = Hook::getIdByName('displayHomeTop3');
        $displayHomeBottom1 = Hook::getIdByName('displayHomeBottom1');
        $displayHomeBottom2 = Hook::getIdByName('displayHomeBottom2');
        $displayHomeBottom3 = Hook::getIdByName('displayHomeBottom3');
        $id_shop = Configuration::get('PS_SHOP_DEFAULT');

        /* install static Block */
        $result = true;
        $result &= Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'ht_staticblocks` (`id_ht_staticblocks`, `hook`, `active`) 
			VALUES
                        (1, "displayHomeTop3", 1),
                        (2, "displayHomeBottom2", 1),
                        (3, "displayFooter", 1)
			;');

        $result &= Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'ht_staticblocks_shop` (`id_ht_staticblocks`, `id_shop`,`active`) 
			VALUES 
			(1,' . $id_shop . ', 1),
			(2,' . $id_shop . ', 1),
			(3,' . $id_shop . ', 1)
			;');

        foreach (Language::getLanguages(false) as $lang) {
            $result &= Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'ht_staticblocks_lang` (`id_ht_staticblocks`, `id_shop`, `id_lang`, `title`, `content`) 
			VALUES 
			("1", "' . $id_shop . '","' . $lang['id_lang'] . '","Header Offer Block", \'' . $content_block1 . '\'),
			("2", "' . $id_shop . '","' . $lang['id_lang'] . '","Home Testimonial Block", \'' . $content_block2 . '\'),
			("3", "' . $id_shop . '","' . $lang['id_lang'] . '","Footer Contact Block", \'' . $content_block3 . '\')
			
                ;');
        }
        return $result;
    }

}
