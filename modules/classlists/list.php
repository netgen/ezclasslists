<?php
//
// $Id: list.php 21 2011-06-22 20:24:05Z dpobel $
// $HeadURL: http://svn.projects.ez.no/ezclasslists/tags/ezclasslists_1_2/ezclasslists/modules/classlists/list.php $
//
// Created on: <14-Jui-2007 15:00 damien pobel>
//
// SOFTWARE NAME: eZ Class Lists
// SOFTWARE RELEASE: 1.2
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 Damien POBEL
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.

$http = eZHTTPTool::instance();
$listIni = eZINI::instance( 'lists.ini' );

$Module = $Params["Module"];
$hasPost = false;
$ajax = false;

$classIdentifier = '';
if ( isset( $Params['classIdentifier'] ) )
{
    $classIdentifier = $Params['classIdentifier'];
}
if ( $http->hasPostVariable( 'classIdentifier' ) )
{
    $classIdentifier = $http->postVariable( 'classIdentifier' );
    $classIdentifier = $classIdentifier === 'all_classes' ? '' : $classIdentifier;
    $hasPost = true;
}
if ( is_numeric( $classIdentifier ) )
{
    $classIdentifier = eZContentClass::classIdentifierByID( $classIdentifier );
}

$sortMethod = $listIni->variable( 'ListSettings', 'DefaultSortMethod' );
if ( isset( $Params['sortMethod'] ) )
{
    $sortMethod = $Params['sortMethod'];
}
if ( $http->hasPostVariable( 'sortMethod' ) )
{
    $sortMethod = $http->postVariable( 'sortMethod' );
    $hasPost = true;
}

$sortOrder = $listIni->variable( 'ListSettings', 'DefaultSortOrder' );
if ( isset( $Params['sortOrder'] ) )
{
    $sortOrder = $Params['sortOrder'];
}
if ( $http->hasPostVariable( 'sortOrder' ) )
{
    $sortOrder = $http->postVariable( 'sortOrder' );
    $hasPost = true;
}

$rootNodeId = 1;
if ( isset( $Params['RootNodeId'] ) && $Params['RootNodeId'] !== false )
{
    $rootNodeId = $Params['RootNodeId'];
}
if ( $http->hasPostVariable( 'rootNodeId' ) )
{
    $rootNodeId = $http->postVariable( 'rootNodeId' );
    $hasPost = true;
}
if ( $http->hasPostVariable('SelectedNodeIDArray' ) )
{
    $rootNodeId = $http->postVariable( 'SelectedNodeIDArray' )[0];
}

$createdDateFrom = null;
if ( isset( $Params['createdDateFrom'] ) && $Params['createdDateFrom'] !== false )
{
    $createdDateFrom = $Params['createdDateFrom'];
}
if ( $http->hasPostVariable( 'createdDateFrom' ) )
{
    $createdDateFrom = $http->postVariable( 'createdDateFrom' );
    $createdDateFrom = DateTime::createFromFormat('Y-m-d H:i', $createdDateFrom . ' 00:00');
    $createdDateFrom = $createdDateFrom->getTimestamp();
    $hasPost = true;
}

$createdDateTo = null;
if ( isset( $Params['createdDateTo'] ) && $Params['createdDateTo'] !== false )
{
    $createdDateTo = $Params['createdDateTo'];
}
if ( $http->hasPostVariable( 'createdDateTo' ) )
{
    $createdDateTo = $http->postVariable( 'createdDateTo' );
    $createdDateTo = DateTime::createFromFormat('Y-m-d H:i', $createdDateTo . ' 00:00');
    $createdDateTo = $createdDateTo->getTimestamp();
    $hasPost = true;
}

$modifiedDateFrom = null;
if ( isset( $Params['modifiedDateFrom'] ) && $Params['modifiedDateFrom'] !== false )
{
    $modifiedDateFrom = $Params['modifiedDateFrom'];
}
if ( $http->hasPostVariable( 'modifiedDateFrom' ) )
{
    $modifiedDateFrom = $http->postVariable( 'modifiedDateFrom' );
    $modifiedDateFrom = DateTime::createFromFormat('Y-m-d H:i', $modifiedDateFrom . ' 00:00');
    $modifiedDateFrom = $modifiedDateFrom->getTimestamp();
    $hasPost = true;
}

$modifiedDateTo = null;
if ( isset( $Params['modifiedDateTo'] ) && $Params['modifiedDateTo'] !== false )
{
    $modifiedDateTo = $Params['modifiedDateTo'];
}
if ( $http->hasPostVariable( 'modifiedDateTo' ) )
{
    $modifiedDateTo = $http->postVariable( 'modifiedDateTo' );
    $modifiedDateTo = DateTime::createFromFormat('Y-m-d H:i', $modifiedDateTo . ' 00:00');
    $modifiedDateTo = $modifiedDateTo->getTimestamp();
    $hasPost = true;
}

$ownerUserId = 0;
if ( isset( $Params['ownerId'] ) && $Params['ownerId'] !== false )
{
    $ownerUserId = $Params['ownerId'];
}
if ( $http->hasPostVariable( 'ownerId' ) )
{
    $ownerUserId= $http->postVariable( 'ownerId' );
    $hasPost = true;
}

if ( isset( $Params['ajax'] ) || $http->hasPostVariable('ajax') )
{
    $ajax = true;
}

if ( $hasPost && !$ajax )
{
    // converting post variables into ordered parameters
    $Module->redirectToView(
        'list',
        array(
            $classIdentifier,
            $sortMethod,
            $sortOrder,
            $rootNodeId,
            $createdDateFrom,
            $createdDateTo,
            $modifiedDateFrom,
            $modifiedDateTo,
            $ownerUserId
        )
    );
}

$offset = $Params['Offset'];
if( !is_numeric( $offset ) )
{
    $offset = 0;
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'sort_method', $sortMethod );
if ( $sortOrder === 'ascending' )
{
    $sortOrderTemplate = true;
}
else
{
    $sortOrderTemplate = false;
}
$tpl->setVariable( 'sort_order', $sortOrderTemplate );


if ( $Module->isCurrentAction('Remove') )
{
    if ( $http->hasPostVariable( 'MoveToTrash' ) && $http->postVariable( 'MoveToTrash' ) == '1' )
    {
        $moveToTrash = true;
    }
    else
    {
        $moveToTrash = false;
    }
    if ( $Module->hasActionParameter( 'DeleteIDArray' ) )
    {
        $nodeIDList = $Module->actionParameter( 'DeleteIDArray' );
        if ( is_array( $nodeIDList ) )
        {
            $removeCount = 0;
            foreach( $nodeIDList as $nodeID )
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID );
                if ( !$node )
                {
                    continue ;
                }
                if ( $node->canRemove() )
                {
                    $node->removeNodeFromTree( $moveToTrash );
                    $removeCount++;
                }
            }
            $tpl->setVariable( 'remove_count', $removeCount );
        }
    }
}

$path = array( array( 'url' => 'classlists/list',
                      'text' => ezpI18n::tr( 'classlists/list', 'Lists by content class' ) ) );

if ( $classIdentifier != '' )
{
    $classObject = eZContentClass::fetchByIdentifier( $classIdentifier );
    if ( is_object( $classObject ) )
    {
        $page_uri = trim( $Module->redirectionURI( 'classlists', 'list', array( $classIdentifier,
                                                                                $sortMethod,
                                                                                $sortOrder
                                                                        ) ), '/' );
        $path[] = array( 'url' => $page_uri,
                         'text' => ezpI18n::tr( 'classlists/list', '%classname objects',
                                                false, array('%classname' => $classObject->attribute( 'name' ) ) ) );
        $tpl->setVariable( 'class_identifier', $classIdentifier );
        $tpl->setVariable( 'page_uri', $page_uri );
    }
    else
    {
        $page_uri = trim( $Module->redirectionURI( 'classlists', 'list', array( '', $sortMethod,
                                                                                $sortOrder ) ), '/' );
        $tpl->setVariable( 'page_uri', $page_uri );
        $tpl->setVariable( 'class_identifier', false );
        $tpl->setVariable( 'error', ezpI18n::tr( 'classlists/list',
                                    '%class_identifier is not a valid content class identifier.',
                                    false, array( '%class_identifier' => $classIdentifier ) )  );
    }

}
else
{
    $tpl->setVariable( 'page_uri', 'classlists/list//' . $sortMethod . '/' . $sortOrder );
    $tpl->setVariable( 'class_identifier', false );
}

$viewParameters = [
    'offset' => $offset,
    'rootNodeId' => $rootNodeId,
    'createdDateFrom' => $createdDateFrom,
    'createdDateTo' => $createdDateTo,
    'modifiedDateFrom' => $modifiedDateFrom,
    'modifiedDateTo' => $modifiedDateTo,
    'ownerId' => $ownerUserId
];
$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'root_node_id', $rootNodeId);
$tpl->setVariable( 'created_date_from', $createdDateFrom);
$tpl->setVariable( 'created_date_to', $createdDateTo);
$tpl->setVariable( 'modified_date_from', $modifiedDateFrom);
$tpl->setVariable( 'modified_date_to', $modifiedDateTo);
$tpl->setVariable( 'owner_user_id', $ownerUserId);

if ( $ajax )
{

    $http->setSessionVariable( "LastAccessesURI", $tpl->variable( 'page_uri' ) );
    echo $tpl->fetch( 'design:classlists/list.tpl' );
    eZDB::checkTransactionCounter();
    eZExecution::cleanExit();
}
else
{
    $Result['content'] = $tpl->fetch( 'design:classlists/list.tpl' );
    $Result['left_menu'] = 'design:classlists/menu.tpl';
    $Result['path'] = $path;
}
?>
