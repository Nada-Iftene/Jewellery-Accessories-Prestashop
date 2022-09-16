{**
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Static Block List' mod='ht_staticblocks'}
        <span class="panel-heading-action">
            <a id="desc-product-new" class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=ht_staticblocks&addblock=1">
                <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new" data-html="true">
                    <i class="process-icon-new "></i>
                </span>
            </a>
        </span>
    </h3>
    {if count($blocks) > 0}
        <table class="table">
            <thead>
                <tr>
                    <th>{l s='ID' mod='ht_staticblocks'}</th>
                    <th>{l s='Title' mod='ht_staticblocks'}</th>
                    <th>{l s='Hook' mod='ht_staticblocks'}</th>
                    <th>{l s='Status' mod='ht_staticblocks'}</th>
                    <th>{l s='Action' mod='ht_staticblocks'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$blocks item=block}
                    <tr>
                        <td>{$block.id_ht_staticblocks|escape:'html':'UTF-8'}</td>
                        <td>{$block.title|escape:'html':'UTF-8'}</td>
                        <td>{$block.hook|escape:'html':'UTF-8'}</td>
                        <td>{if $block.active=='1'}{l s='Enabled' mod='ht_staticblocks'}{else}{l s='Disabled' mod='ht_staticblocks'}{/if}</td>
                        <td>
                            <div class="btn-group-action">
                                <a class="btn btn-default"
                                   href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=ht_staticblocks&id_ht_staticblocks={$block.id_ht_staticblocks|intval}">
                                    <i class="icon-edit"></i>
                                    {l s='Edit' mod='ht_staticblocks'}
                                </a>
                                <a class="btn btn-default"
                                   href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=ht_staticblocks&delete_id_block={$block.id_ht_staticblocks|intval}">
                                    <i class="icon-trash"></i>
                                    {l s='Delete' mod='ht_staticblocks'}
                                </a>
                                </div> 
                            </td>
                        </tr>
                        {/foreach}
                        </tbody>
                    </table>
                    {/if}
                    </div>