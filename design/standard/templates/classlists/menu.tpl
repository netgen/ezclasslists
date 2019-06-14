{*
 * $Id: menu.tpl 18 2011-06-22 19:53:59Z dpobel $
 * $HeadURL: http://svn.projects.ez.no/ezclasslists/tags/ezclasslists_1_2/ezclasslists/design/standard/templates/classlists/menu.tpl $
 *}

{def $classlist = fetch( 'class', 'list',
                                        hash(
                                            'class_filter',
                                            ezini( 'ListSettings', 'IncludeClasses', 'lists.ini' ),
                                            'sort_by', array( 'name', true() ) )
                                        )
}

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h4>{'Options'|i18n( 'classlists/list' )}</h4>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

    {def $root_node_id = '1'}
    {if not($view_parameters.rootNodeId|eq(null))}
        {set $root_node_id = $view_parameters.rootNodeId}

        {def $root_node = fetch(content, node, hash(node_id, $root_node_id ) )}

    {if ezhttp( 'SelectedNodeIDArray', 'POST' )}
        {set $root_node_id = ezhttp( 'SelectedNodeIDArray', 'POST' )}
        {set $root_node_id = $root_node_id[0]}

        {def $root_node = fetch(content, node, hash(node_id, $root_node_id ) )}
    {/if}

    <form method="post" action={'/content/action'|ezurl()} id="move">
        {if $root_node}
            <input type="hidden" name="ContentNodeID" value="{$root_node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$root_node_id}" />
        {else}
            <input type="hidden" name="ContentNodeID" value="2" />
            <input type="hidden" name="ContentObjectID" value="2" />
        {/if}

        <input type="submit" name="CustomBrowse" class="btn btn-primary" value="Select subtree" />
    </form>

    <form action={$page_uri|ezurl()} method="post" id="class-list-menu-form">
    {def $classlist = fetch( 'class', 'list', hash( 'class_filter', ezini( 'ListSettings', 'IncludeClasses', 'lists.ini' ),
                                                    'sort_by', array( 'name', true() ) ) )
         $uri = ''}

    {if $root_node}
        <label>{'Selected subtree:'|i18n( 'classlists/list' )}</label>
        <a href={$root_node.url_alias|ezurl()}>{$root_node.name|wash()}</a>
    {/if}

    <input type="hidden" name="RootNodeId" id="rootNodeId" value="{$root_node_id}" />

    <label for="classIdentifier">{'Classes list'|i18n( 'classlists/list' )}</label>
    <select name="classIdentifier" id="classIdentifier">
    {foreach $classlist as $class}
        <option value="{$class.identifier|wash()}"{cond( $class_identifier|eq( $class.identifier ), ' selected="selected"' , '' )}>{$class.name|wash()}</option>
    {/foreach}
    </select>
    {undef $classlist $uri}

    <label for="sortMethod">{'Sort by'|i18n( 'classlists/list' )}</label>
    {def $sort_methods = hash( 'depth', 'Depth',
                               'name', 'Name',
                               'path', 'Path',
                               'path_string', 'Path string',
                               'priority', 'Priority',
                               'modified', 'Modified',
                               'published', 'Published',
                               'section', 'Section' )}
    <select name="sortMethod" id="sortMethod">
        {foreach $sort_methods as $key => $sm}
        <option value="{$key}"
                       {cond( $key|eq( $sort_method ), ' selected="selected"', '' )}
                       title="{$sm|i18n( 'classlists/list' )}">{$sm|i18n( 'classlists/list' )|shorten( 20 )}</option>
        {/foreach}
    </select>
    {undef $sort_methods}


    <label for="sortOrder">{'Sort order'|i18n( 'classlists/list' )}</label>
    <select name="sortOrder" id="sortOrder">
        <option value="ascending"{cond( $sort_order, ' selected="selected"', '' )}>{'Ascending'|i18n( 'classlists/list' )}</option>
        <option value="descending"{cond( $sort_order|not, ' selected="selected"', '' )}>{'Descending'|i18n( 'classlists/list' )}</option>
    </select>

    <label for="createdDateFrom">{'Created since'|i18n( 'classlists/list' )}</label>
    <input type="text" name="createdDateFrom" id="createdDateFrom" {if $view_parameters.createdDateFrom}value="{$view_parameters.createdDateFrom|datetime('custom', '%d/%m/%Y')}"{/if} />

    <label for="createdDateTo">{'Created until'|i18n( 'classlists/list' )}</label>
    <input type="text" name="createdDateTo" id="createdDateTo" {if $view_parameters.createdDateTo}value="{$view_parameters.createdDateTo|datetime('custom', '%d/%m/%Y')}"{/if} />

    <label for="modifiedDateFrom">{'Modified since'|i18n( 'classlists/list' )}</label>
    <input type="text" name="modifiedDateFrom" id="modifiedDateFrom" {if $view_parameters.modifiedDateFrom}value="{$view_parameters.modifiedDateFrom|datetime('custom', '%d/%m/%Y')}"{/if} />

    <label for="modifiedDateTo">{'Modified until'|i18n( 'classlists/list' )}</label>
    <input type="text" name="modifiedDateTo" id="modifiedDateTo" {if $view_parameters.modifiedDateTo}value="{$view_parameters.modifiedDateTo|datetime('custom', '%d/%m/%Y')}"{/if} />

    {def $parent_node_ids = ezini( 'Users', 'UserGroupIds', 'lists.ini' )}
    {def $users = array()}
    {foreach $parent_node_ids as $parent_node_id}
        {def $current_users = fetch(
                        content,
                        tree,
                        hash(
                            'parent_node_id', $parent_node_id,
                            'sort_by', array( 'name', false() ),
                            'class_filter_type', include,
                            'class_filter_array', array( ezini( 'Users', 'UserClassIdentifiers', 'lists.ini' ) ),
                            'main_node_only', true()
                        )
                    )
        }

        {set $users = $users|array_merge($current_users)}
    {/foreach}

    <label for="ownerUser">{'Owner'|i18n( 'classlists/list' )}</label>
    <select name="ownerUser" id="ownerUser">
        <option value="0">{*any user*}</option>
        {foreach $users as $user}
            <option value="{$user.contentobject_id}">{$user.name}</option>
        {/foreach}
    </select>

    <p>
        <input type="submit" class="button" value="{'Go'|i18n( 'classlists/list' )}" />
    </p>

    </form>
</div></div></div></div></div></div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript">
YUILoader.require(['utilities', 'event']);
YUILoader.onSuccess = function() {ldelim}

    var o = {ldelim} baseURL: {'classlists/list'|ezurl( 'single' )}, loader: {'loader.gif'|ezimage( 'single' )} {rdelim};
    YAHOO.util.Event.addListener("class-list-menu-form", "submit", classListsFilter, o);

{rdelim};
YUILoader.insert({ldelim}{rdelim}, 'js');

{literal}

$( function() {
    $( "#createdDateFrom").datepicker();
    $( "#createdDateTo").datepicker();
    $( "#modifiedDateFrom").datepicker();
    $( "#modifiedDateTo").datepicker();
});
{/literal}

</script>

