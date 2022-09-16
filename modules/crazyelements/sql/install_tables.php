<?php
/**
 * 2020 PrestaShop
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
 * @copyright 2020 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . "crazy_options` (
    `id_options` int(11) NOT NULL,
    `id` int(11) NOT NULL DEFAULT '0',
    `id_lang` int(11) NOT NULL DEFAULT '0',
    `id_shop` int(11) NOT NULL DEFAULT '0',
    `option_name` varchar(100) NOT NULL,
    `option_value` longtext NOT NULL,
    `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;";

$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_options`
ADD PRIMARY KEY (`id_options`);';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_options`
MODIFY `id_options` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;';

// ------------------------------------------------------------------------


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_library` (
    `id_crazy_library` int(100) NOT NULL,
    `data` longtext NOT NULL,
    `elements` longtext NOT NULL,
    `settings` longtext NOT NULL,
    `title` varchar(100) NOT NULL,
    `status` varchar(100) NOT NULL,
    `type` varchar(100) NOT NULL,
    `post_type` varchar(100) NOT NULL,
    `source` varchar(100) NOT NULL,
    `thumbnail` varchar(255) DEFAULT NULL,
    `date` varchar(100) DEFAULT NULL,
    `human_date` datetime DEFAULT NULL,
    `author` varchar(100) DEFAULT NULL,
    `hasPageSettings` varchar(100) DEFAULT NULL,
    `tags` varchar(100) DEFAULT NULL,
    `export_link` text NOT NULL,
    `url` text NOT NULL
  ) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_library`
ADD PRIMARY KEY (`id_crazy_library`);';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_library`
MODIFY `id_crazy_library` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;';

// ------------------------------------------------------------------------
//$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_content_shop` (
//    `id_crazy_content_shop` int(11) NOT NULL AUTO_INCREMENT,
//    `id_crazy_content` int(11) NOT NULL,
//    `id_shop` int(11) NOT NULL,
//    KEY(`id_crazy_content_shop`),
//  PRIMARY KEY (`id_crazy_content`,`id_shop`)
//  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_content_shop` ( 
    `id_crazy_content` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_content_shop`
ADD UNIQUE KEY `id_crazy_content` (`id_crazy_content`,`id_shop`);';
  // ------------------------------------------------------------------------
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_content_lang` (
    `id_crazy_content` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` varchar(100) NOT NULL,
    `resource` longtext NOT NULL,
    `id_shop`  int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_content_lang`
ADD UNIQUE KEY `id_crazy_content` (`id_crazy_content`,`id_lang`);';
// ------------------------------------------------------------------------
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_content` (
    `id_crazy_content` int(11) NOT NULL,
    `id_content_type` int(11) NOT NULL,
    `hook` varchar(100) NOT NULL,
     `active` int(1) NOT NULL,
    `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_content`
ADD PRIMARY KEY (`id_crazy_content`);';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_content`
MODIFY `id_crazy_content` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_setting` (
  `ps_crazy_id` int(100) NOT NULL,
  `post_id` int(100) NOT NULL,
  `settings` text NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_setting`
ADD PRIMARY KEY (`ps_crazy_id`)';

$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_setting`
MODIFY `ps_crazy_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;';

$sql[] = 'CREATE TABLE  ' . _DB_PREFIX_ . 'crazy_revision (
  id_crazy_revision int NOT NULL AUTO_INCREMENT,
  id_lang int(11) NOT NULL,
  id_shop int(11) NOT NULL,
  id_post int(11) NOT NULL,
  title varchar(100) NOT NULL,
  resource longtext NOT NULL,
  type varchar(100) NOT NULL,
  post_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  revision_type varchar(100) ,
  settings longtext NOT NULL,
 
  PRIMARY KEY (id_crazy_revision)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';



$sql[] = 'CREATE TABLE  ' . _DB_PREFIX_ . 'crazy_fonts (
  `id_crazy_fonts` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `font_weight` varchar(255) NOT NULL,
  `font_style` varchar(256) NOT NULL,
  `woff` varchar(256) NOT NULL,
  `woff2` varchar(256) NOT NULL,
  `ttf` varchar(256) NOT NULL,
  `svg` varchar(256) NOT NULL,
  `eot` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fontname` varchar(100) NOT NULL,
 
  PRIMARY KEY (id_crazy_fonts)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_autocomplete_products`(
  `id_autocomplete_products` int(11) NOT NULL auto_increment,
  `prd_specify` LONGTEXT DEFAULT NULL,
  `prd_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id_autocomplete_products`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'crazy_extended_modules` (
  `id_crazy_extended_modules` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `controller_name` varchar(100) NOT NULL,
  `front_controller_name` varchar(255) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `extended_item_key` varchar(300) NOT NULL,
  `active` int(1) NOT NULL
  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_extended_modules`
ADD PRIMARY KEY (`id_crazy_extended_modules`);';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'crazy_extended_modules`
MODIFY `id_crazy_extended_modules` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;';


foreach ( $sql as $query ) {
	if ( Db::getInstance()->execute( $query ) == false ) {
		return false;
	}
}
