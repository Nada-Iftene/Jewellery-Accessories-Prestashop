{if $slider_enable == 1}
    <ul id="brands-slider" class="brands-slider">
        {foreach from=$brands item=brand name=brand_list}
            {if $smarty.foreach.brand_list.iteration <= $text_list_nb}
                <li class="item">
                    <a href="{$brand['link']}" title="{$brand['name']}">
                        <img src="{$brand['imageurl']}" title="{$brand['name']}" alt="{$brand['name']}" /> 
                    </a>
                </li>
            {/if}
        {/foreach}
    </ul>
{/if}