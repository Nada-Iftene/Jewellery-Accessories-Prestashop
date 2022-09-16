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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2020 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
$id_parent = Tab::getIdFromClassName('AdminCrazyMain');
$tabvalue = array(
    array(
        'class_name' => 'AdminCrazyEditor',
        'id_parent' => $id_parent,
        'module' => 'crazyelements',
        'name' => 'Crazy Editors',
        'icon' => 'brush',
    ),

    array(
        'class_name' => 'AdminCrazyFonts',
        'id_parent' => $id_parent,
        'module' => 'crazyelements',
        'name' => 'Font Manager',
        'active' => 1,
    ),
    array(
        'class_name' => 'AdminCrazyPseIcon',
        'id_parent' => $id_parent,
        'module' => 'crazyelements',
        'name' => 'Icon Manager',
        'active' => 1,
    ),
    array(
        'class_name' => 'AdminCrazySetting',
        'id_parent' => $id_parent,
        'module' => 'crazyelements',
        'name' => 'Settings',
        'active' => 1,
        'icon' => 'settings',
    ),
    array(
        'class_name' => 'AdminCrazyExtendedmodules',
        'id_parent' => $id_parent,
        'module' => 'crazyelements',
        'name' => 'Extend Third Party Modules',
        'active' => 1,
    ),
    array(
        'class_name' => 'AdminCrazyFrontendEditor',
        'id_parent' => -1,
        'module' => 'crazyelements',
        'name' => 'AdminCrazyFrontendEditor',
        'active' => 1,
    ),
     array(
        'class_name' => 'AdminCrazyAjaxUrl',
        'id_parent' => -1,
        'module' => 'crazyelements',
        'name' => 'AdminCrazyAjaxUrl',
         'active' => 1,
     ),
     array(
        'class_name' => 'AdminCrazyImages',
        'id_parent' => -1,
        'module' => 'crazyelements',
        'name' => 'Image Type',
         'active' => 1,
    )
);