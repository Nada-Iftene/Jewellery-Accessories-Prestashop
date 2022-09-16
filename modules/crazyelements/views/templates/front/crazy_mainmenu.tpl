{assign var=_counter value=0}
{function name="menu" nodes=[] depth=0 parent=null}
    <ul class="nav navbar-nav">
        {foreach from=$nodes item=node}
            {if $node.children|count >= 1}
                {assign var=_expand_id value=111|mt_rand:999}
                <li class="dropdown" data-depth="{$depth}"><a href="{$node.url}">{$node.label} <span class="toggle-caret" data-toggle="collapse" data-target="#dropdown_menu{$_expand_id}"><span class="caret"></span></span></a>
                    <ul id="dropdown_menu{$_expand_id}" class="dropdown-menu">
                        {foreach from=$node.children item=nodechild}
                            {if $nodechild.children|count >= 1}
                                {assign var=_expand_id value=111|mt_rand:999}
                                <li class="dropdown" data-depth="{$node.depth}"><a href="{$nodechild.url}">{$nodechild.label} <span class="toggle-caret" data-toggle="collapse" data-target="#dropdown_menu{$_expand_id}"><span class="caret"></span></span></a>
                                    <ul id="dropdown_menu{$_expand_id}" class="dropdown-menu">
                                        {foreach from=$nodechild.children item=nodechild2}
                                            <li><a href="{$nodechild2.url}">{$nodechild2.label}</a></li>
                                        {/foreach}
                                    </ul>
                                </li>
                            {/if}
                            {if $nodechild.children|count == 0}
                                <li><a href="{$nodechild.url}">{$nodechild.label}</a></li>
                            {/if}
                        {/foreach}
                    </ul>
                </li>
            {/if}
            {if $node.children|count == 0}
                <li {if $node.current} class="active" {/if} data-depth="{$depth}"><a href="{$node.url}">{$node.label}</a></li>
            {/if}
        {/foreach}
    </ul>  
{/function}
{menu nodes=$menu.children}