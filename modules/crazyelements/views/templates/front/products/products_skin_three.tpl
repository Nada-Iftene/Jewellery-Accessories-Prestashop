
    {if isset($crazy_products) && $crazy_products}
    <div class="product-grid-wrapper  vc-smart-{$elementprefix}-products-grid ce_pr {$skin_class}">
        {if !empty($section_heading)}
            <p class="title_block">{$section_heading}</p>
        {/if}
        {assign var="six_col_class" value=""}
        <div class="products">
            <div class="container">
                <div class="row ce_pr_row">
                {if $column_width}
                    {$column = $column_width}
                {else}
                    {$column = 'col-lg-4 col-md-6 col-sm-12 col-xs-12'}
                {/if}

                {if $column_width == 'col-lg-2 col-md-6 col-sm-12 col-xs-12'}  
                    {if $quantity_spin eq 'yes'}
                        {assign "six_col_class" "six-col-class"}
                    {/if}
                {/if}
                    {foreach from=$crazy_products item="product"}
                        <div class="{$column} {$six_col_class}">
                            <div class="product_inner_style3 cr-pr-inner">
                                <div class="thumbnail">
                                    <img src="{$product.cover.bySize.large_default.url}">
                                    {if $product.discount_percentage || $product.discount_amount}
                                        <div class="product_flag">
                                            {if $ed_dis_percent}
                                                {if $product.discount_percentage}
                                                    <p class="discount_percentage">{$product.discount_percentage_absolute}</p>
                                                {/if}
                                            {/if}
                                            {if $ed_dis_amount}
                                                {if $product.discount_amount}
                                                    <p class="discount_amount">-{$product.discount_amount}</p>
                                                {/if}
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
                                                        {block name='quick_view'}
                                                            <a class="quick-view js-quick-view" href="{$product.url}" data-link-action="quickview">
                                                            <i class="material-icons search">&#xE8B6;</i>
                                                            {l s='' d='Shop.Theme.Actions'}
                                                            </a>
                                                        {/block}
                                                        {if $quantity_spin eq 'yes'}
                                                            <input type="number" class="product_q_spin" name="qty" value="1" min="1">
                                                        {else}
                                                            <input type="hidden" class="input-group form-control" name="qty" value="1" min="1" aria-label="{l s='Quantity' d='Shop.Theme.Actions'}">
                                                        {/if}
                                                        <button class="btn add_to_cart_btn" data-button-action="add-to-cart" type="submit" {if !$product.add_to_cart_url} disabled {/if} >
                                                            {l s='Add to cart' d='Shop.Theme.Actions'}
                                                        </button>
                                                        {* {hook h='displayProductActions' product=$product} *}
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
                                            {if $from_cat_addon}
                                                <p class="category_name">{$from_cat_addon}</p>
                                            {else}
                                                <p class="category_name">{$product.category_name}</p>
                                            {/if}
                                        {/if}
                                    </div>
                                    <a href="{$product.link}"><h4 class="name">{$product.name}</h4></a>
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
                                         <p class="regular_price {$adv_class}">{$product.regular_price}</p>
                                        {if $product.regular_price != $product.price}
                                            <p class="price">{$product.price}</p>
                                        {/if}
                                    </div>
            
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
{/if}
