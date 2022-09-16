{*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="brand-section">
    <div class="container">
        <div class="section-title">
            <h2 class="title">
                {if $display_link_brand}<a href="{$page_link}" title="{l s='Brands' d='Modules.Brandlist.Shop'}">{/if}
                    {l s='Brands' d='Modules.Brandlist.Shop'}
                    {if $display_link_brand}</a>{/if}
            </h2>
        </div>
        <div class="brand-content">
            <div class="brand-row">
                {if $brands}
                    {include file="module:ht_brandlist/views/templates/_partials/$brand_display_type.tpl" brands=$brands}
                {else}
                    <p>{l s='No brand' d='Modules.Brandlist.Shop'}</p>
                {/if}
            </div>
        </div>
    </div>
</div>
