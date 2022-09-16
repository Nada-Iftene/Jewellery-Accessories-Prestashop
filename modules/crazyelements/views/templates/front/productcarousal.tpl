{if isset($vc_products) && $vc_products}
    <div class="elementor-image-carousel-wrapper elementor-slick-slider">
        <div class="elementor-image-carousel products slick-arrows-inside slick-dots-outside" >
            {foreach from=$vc_products item="product"}
            <div class="slick-slide">
                <div class="slick-slide-inner">
              {include file="$theme_template_path" product=$product}
              </div>
               </div>
            {/foreach}
        </div>
    </div>
{/if}