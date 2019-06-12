<?php

function ezclasslists_ContentActionHandler( $module, $http, $objectID )
{
    if( $http->hasPostVariable("CustomBrowse") )
    {
        $module->setCurrentAction("CustomBrowse");
    }

    if ( $module->isCurrentAction("CustomBrowse") )
    {
        //$class = eZContentClass::fetchByIdentifier('image');
        eZContentBrowse::browse(
            array(
                'action_name' => $module->currentAction(),
                'description_template' => 'design:content/browse_move_node.tpl',
                'keys' => array(
                    //'class' => $class->attribute( 'id' ),
                    //'class_id' => $class->attribute( 'identifier' ),
                    //'classgroup' => $class->attribute( 'ingroup_id_list' ),
                ),
                'permission' => array(
                    //'access' => 'create',
                    //'contentclass_id' => $class->attribute( 'id' )
                ),
                'start_node' => '2',
                'cancel_page' => '/classlists/list', // @todo: make it smarter if possible
                'from_page' => "/classlists/list" ), // @todo: make it smarter if possible
            $module
        );

        return;
    }
}
