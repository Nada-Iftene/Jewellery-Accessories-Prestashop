{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
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
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
 
<div id="blog-section" class="ht-home-blogs slider-block">
    <div class="container">
        {if isset($xipbdp_title)}
            <div class="section-title">
                <h2>
                    <span>
                        <a href="{xipblog::XipBlogLink()}">{$xipbdp_title}</a>
                    </span>
                </h2>
                {* {if isset($xipbdp_subtext)}
                <p class="page_subtitle">{$xipbdp_subtext}</p>
                {/if}*}
            </div>
        {/if}
        <div class="content-block row">
            <div class="blog-slider">
                {if (isset($xipblogposts) && !empty($xipblogposts))}
                    {foreach from=$xipblogposts item=xipblgpst}
                        <div class="item">
                            <div class="item-inner">
                                <div class="img-block">
                                    <a href="{$xipblgpst.link}">
                                        <div class="post_thumbnail">
                                            {if $xipblgpst.post_format == 'video'}
                                                {assign var="postvideos" value=','|explode:$xipblgpst.video}
                                                {if $postvideos|@count > 1 }
                                                    {include file="module:xipblog/views/templates/front/post-video.tpl" videos=$postvideos width='570' height="316" class="carousel"}
                                                {else}
                                                    {include file="module:xipblog/views/templates/front/post-video.tpl" videos=$postvideos width='570' height="316" class=""}
                                                {/if}
                                            {elseif $xipblgpst.post_format == 'audio'}
                                                {assign var="postaudio" value=','|explode:$xipblgpst.audio}
                                                {if $postaudio|@count > 1 }
                                                    {include file="module:xipblog/views/templates/front/post-audio.tpl" audios=$postaudio width='570' height="316" class="carousel"}
                                                {else}
                                                    {include file="module:xipblog/views/templates/front/post-audio.tpl" audios=$postaudio width='570' height="316" class=""}
                                                {/if}
                                            {elseif $xipblgpst.post_format == 'gallery'}
                                                {if $xipblgpst.gallery_lists|@count > 1 }
                                                    {include file="module:xipblog/views/templates/front/post-gallery.tpl" gallery=$xipblgpst.gallery_lists imagesize="home_default" class="carousel"}
                                                {else}
                                                    {include file="module:xipblog/views/templates/front/post-gallery.tpl" gallery=$xipblgpst.gallery_lists imagesize="home_default" class=""}
                                                {/if}
                                            {else}
                                                <img src="{$xipblgpst.post_img_home_default}" alt="{$xipblgpst.post_title}">
                                            {/if}
                                        </div>
                                    </a>
                                </div>
                                <div class="text-block">
                                    <div class="post-info">
                                        <span class="blog-date">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            {$xipblgpst.post_date|date_format:"%d %b %Y"}
                                        </span>
                                    </div>
                                    <div class="post-content">
                                        <a href="{$xipblgpst.link}" class="blog-title">
                                            <span>
                                                {$xipblgpst.post_title}
                                            </span>
                                        </a>
                                        {*{if isset($xipblgpst.post_excerpt) && !empty($xipblgpst.post_excerpt)}
                                        <div class="blog-desc">
                                        {$xipblgpst.post_excerpt|truncate:100:' ...'|escape:'html':'UTF-8'}
                                        </div>
                                        {else}
                                        <div class="blog-desc">
                                        {$xipblgpst.post_content|truncate:100:' ...'|escape:'html':'UTF-8'}
                                        </div>
                                        {/if}*}
                                    </div>
                                    <div class="link-more">
                                        <a href="{$xipblgpst.link}" class="blog-title">
                                            <span>
                                                {l s='Read more' mod='xipblog'} <i class="fa fa-long-arrow-right"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                {else}
                    <p>{l s='No Blog Post Found' mod='xipblog'}</p>
                {/if}
            </div>
        </div>
    </div>
</div>