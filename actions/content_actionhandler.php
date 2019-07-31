<?php

function ezclasslists_ContentActionHandler( $module, $http, $objectID )
{
    if( $http->hasPostVariable("CustomBrowse") )
    {
        $module->setCurrentAction("CustomBrowse");
    }

    if ( $module->isCurrentAction("CustomBrowse") )
    {
        $startNode = $http->postVariable("ContentNodeID");

        eZContentBrowse::browse(
            array(
                'action_name' => $module->currentAction(),
                'description_template' => 'design:classlists/browse.tpl',
                'start_node' => $startNode,
                'cancel_page' => '/classlists/list',
                'from_page' => "/classlists/list" ),
            $module
        );

        return;
    }
}
