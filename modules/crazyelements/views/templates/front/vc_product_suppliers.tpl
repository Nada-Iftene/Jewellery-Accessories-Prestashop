<div class="elementor-image-carousel-wrapper elementor-slick-slider">
    <div class="elementor-image-carousel slick-arrows-inside slick-dots-outside" >
        {foreach from=$suppliers item=manufacturer name=manufacturers}
            <a href="{$link->getSupplierLink($manufacturer.id_supplier)}"> 
                {$imgname=$manufacturer.id_supplier}
                {if !empty($man_img_size)}
                    {$imgname=$imgname|cat:'-'|cat:$man_img_size}
                {/if}
                {$imgname=$imgname|cat:'.jpg'}
                <img src="{$img_sup_dir}{$imgname}" alt="{$manufacturer.name}" title="{$manufacturer.name}"  />
            </a>
          {/foreach}
    </div>
</div>