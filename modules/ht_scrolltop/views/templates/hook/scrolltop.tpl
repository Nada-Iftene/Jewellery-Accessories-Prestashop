{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<a class="scrollTop" href="#" title="Scroll To Top">
    <span>
        {if isset($scroll_text_icon) && $scroll_text_icon=='scroll_icon'}
            <i class="fa {$scroll_icon_value}" aria-hidden="true"></i>
        {else}
            {$scroll_text_value}
        {/if}
    </span>
</a>
<style>
    .scrollTop {
        bottom: 90px;
        cursor: pointer;
        display: none;
        font-size: 11px;
        font-weight: 600;
        min-height: 45px;
        line-height: 45px;
        padding: 0;
        position: fixed;
        text-align: center;
        text-transform: uppercase;
        width: 45px;
        z-index: 1;
        color: #000000;
        border: 1px solid #e1e1e1;
        background: #ffffff;
        text-decoration: none;
        outline: none;
    }
    .scrollTop:visited, .scrollTop:focus, .scrollTop:active {
        color: #000000;
        border: 1px solid #e1e1e1;
        background: #ffffff;
        text-decoration: none;
        outline: none;
    }
    .scrollTop:hover {
        color: #ffffff;
        border: 1px solid #000000;
        background: #000000;
        text-decoration: none;
        outline: none;
    }
    @media(max-width: 767px) {
        .scrollTop {
            bottom: 30px;
        }
    }
    {if isset($scroll_position) && $scroll_position == 'scroll_right'}
        .scrollTop {
            right: 25px;
        }
    {/if}
    {if isset($scroll_position) && $scroll_position == 'scroll_left'}
        .scrollTop {
            left: 30px;
        }
    {/if}
    {if isset($scroll_shape) && $scroll_shape == 'scroll_round'}
        .scrollTop {
            border-radius: 50%;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
        }
    {/if}
    .scrollTop {
        {if $scroll_text_size != ''}
            font-size: {$scroll_text_size};
        {/if}
        {if $scroll_back_color != ''}
            background: {$scroll_back_color};
        {/if}
        {if $scroll_border_color != ''}
            border-color: {$scroll_border_color};
        {/if}
        {if $scroll_text_color != ''}
            color: {$scroll_text_color};
        {/if}
    }
    .scrollTop:visited, .scrollTop:focus, .scrollTop:active{
        {if $scroll_back_color != ''}
            background: {$scroll_back_color};
        {/if}
        {if $scroll_border_color != ''}
            border-color: {$scroll_border_color};
        {/if}
        {if $scroll_text_color != ''}
            color: {$scroll_text_color};
        {/if}
    }
    .scrollTop:hover {
        {if $scroll_back_hover_color != ''}
            background: {$scroll_back_hover_color};
        {/if}
        {if $scroll_border_hover_color != ''}
            border-color: {$scroll_border_hover_color};
        {/if}
        {if $scroll_text_hover_color != ''}
            color: {$scroll_text_hover_color};
        {/if}
    }

</style>
