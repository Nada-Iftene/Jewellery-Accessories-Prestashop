{if isset($crazy_products) && $crazy_products}
    <div class="product-grid-wrapper product-miniature vc-smart-{$elementprefix}-products-grid">
	{if !empty($section_heading)}
		<p class="title_block">{$section_heading}</p>
	{/if}
        <div class="products">
            {foreach from=$crazy_products item="product"}
              {include file="$theme_template_path" product=$product}
            {/foreach}
        </div>
    </div>
{/if}