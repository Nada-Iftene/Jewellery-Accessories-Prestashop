{if isset($vc_products) && $vc_products}
    {if !empty($vc_title)}
        <p class="title_block">{$vc_title}</p>
    {/if}
    <div class="elementor-image-carousel-wrapper elementor-slick-slider ce_pr {$skin_class}">
        <div class="elementor-image-carousel slick-arrows-inside slick-dots-outside" >   
            {foreach from=$vc_products item="product"}
                <div class="slick-slide">
                <div class="slick-slide-inner">
                <div class="ce_pr_row">
                    <div class="product_inner">
                        <div class="thumbnail">
                            <img src="{$product.cover.bySize.large_default.url}">
                            {if $product.discount_percentage || $product.discount_amount}
                                <div class="product_flag">
                                    {if $product.discount_percentage}
                                        <p class="discount_percentage">{$product.discount_percentage}</p>
                                    {/if}
                                    {if $product.discount_amount}
                                        <p class="discount_amount">{$product.discount_amount}</p>
                                    {/if}
                                </div>
                            {/if}
                            <div class="add_to_cart">
                                <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                                    <input type="hidden" name="token" value="{$static_token}">
                                    <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                                    {block name='product_add_to_cart'}
                                        {if !$configuration.is_catalog}
                                            {block name='product_quantity'}
                                            <div class="product-quantity clearfix">
                                                <input type="hidden" name="qty" value="1" min="1" aria-label="{l s='Quantity' d='Shop.Theme.Actions'}">
                                                <button class="btn add_to_cart_btn" data-button-action="add-to-cart" type="submit" {if !$product.add_to_cart_url} disabled {/if} >
                                                    <i class="material-icons shopping-cart">&#xE547;</i>
                                                    {l s='Add to cart' d='Shop.Theme.Actions'}
                                                </button>
                                                {hook h='displayProductActions' product=$product}
                                            </div>
                                            {/block}

                                            {block name='product_availability'}
                                                    {if $product.show_availability && $product.availability_message}
                                                        <div class="avail_msg">
                                                            {if $product.availability == 'available'}
                                                                <i class="material-icons rtl-no-flip product-available">&#xE5CA;</i>
                                                            {elseif $product.availability == 'last_remaining_items'}
                                                                <i class="material-icons product-last-items">&#xE002;</i>
                                                            {else}
                                                                <i class="material-icons product-unavailable">&#xE14B;</i>
                                                            {/if}
                                                            {$product.availability_message}
                                                        </div>
                                                    {/if}
                                            {/block}
                                        {/if}
                                    {/block}
                                </form>
                            </div>
                        </div>
                        <div class="product_desc">
                            <div class="texonom">
                                {if $product.manufacturer_name && $ed_manufacture}
                                    <p class="manufacturer_name">{$product.manufacturer_name}</p>
                                {/if}
                                {if $product.supplier_name && $ed_supplier}
                                    <p class="supplier_name">{$product.supplier_name}</p>
                                {/if}
                                {if $product.category_name && $ed_catagories}
                                    <p class="category_name">{$product.category_name}</p>
                                {/if}
                            </div>
                            <a href="{$product.link}"><h2 class="name">{$product.name}</h2></a>
                            {if $ed_short_desc}
                                <p class="description_short">{$product.description_short|strip_tags:'UTF-8'}</p>
                            {/if}
                            {if $ed_desc}
                                <p class="description">{$product.description|strip_tags:'UTF-8'}</p>
                            {/if}
                            
                            <div class="product_info">
                                {if $product.regular_price != $product.price}
                                    {$adv_class = 'has_discount'}
                                {else}
                                    {$adv_class = null}
                                {/if}
                                {* <p class="quantity">{$product.quantity}</p> *}
                                <p class="regular_price {$adv_class}">{$product.regular_price}</p>
                                {if $product.regular_price != $product.price}
                                    <p class="price">{$product.price}</p>
                                {/if}
                            </div>
                            
                        </div>
                    </div>
                </div>
                </div>
                </div>
            {/foreach}
        </div>
    </div>
{/if}