<div class="elementor-image-carousel-wrapper elementor-slick-slider">
    <div class="elementor-image-carousel slick-arrows-inside slick-dots-outside" >
        {foreach from=$manufacturers item=manufacturer name=manufacturers}
        <div class="slick-slide">
            <div class="slick-slide-inner">
            <a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)}"> 
                {$imgname=$manufacturer.id_manufacturer}
                {if !empty($man_img_size)}
                    {$imgname=$imgname|cat:'-'|cat:$man_img_size}
                {/if}
                {$imgname=$imgname|cat:'.jpg'}
                <img src="{$img_manu_dir}{$imgname}" alt="{$manufacturer.name}" title="{$manufacturer.name}"  />
                {$manufacturer.name}
            </a>
            </div>
            </div>
        {/foreach}
    </div>
</div>