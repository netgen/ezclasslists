<hr/>

<a id="menu-class-list" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
   onclick="ezpopmenu_submitForm( 'menu-form-class-list' ); return false;">{"Class list under node"|i18n("classlists/list")}</a>


<form id="menu-form-class-list" method="POST" action={"/classlists/list"|ezurl}>
    <input type="hidden" name="SelectedNodeIDArray[]" value="%nodeID%" />
</form>
