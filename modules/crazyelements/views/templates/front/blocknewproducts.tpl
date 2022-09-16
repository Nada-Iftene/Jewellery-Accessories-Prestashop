{if isset($vc_products) && $vc_products}
    <div class="product-grid-wrapper product-miniature vc-smart-{$elementprefix}-products-grid">
	{if !empty($vc_title)}
		<p class="title_block">{$vc_title}</p>
	{/if}
        <div class="products">
        
            {foreach from=$vc_products item="product"}
              {include file="$theme_template_path" product=$product}
            {/foreach}
        </div>
    </div>
{/if}