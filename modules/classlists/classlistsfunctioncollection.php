<?php

class classlistsFunctionCollection
{
    public static function fetchClassListInSubtree($parentId)
    {
        $db = eZDB::instance();

        $query = 'SELECT COUNT(*) as count, ezcontentclass.identifier as class_identifier, ezcontentclass.id as class_id 
                FROM ezcontentobject_tree 
                JOIN ezcontentobject ON ezcontentobject_tree.contentobject_id=ezcontentobject.id 
                JOIN ezcontentclass ON ezcontentclass.id=ezcontentobject.contentclass_id 
                WHERE path_string LIKE \'%/'.$parentId.'/%\' 
                AND ezcontentobject_tree.node_id != '.$parentId.' 
                GROUP BY contentclass_id
                ORDER BY count desc'
        ;

        $rows = $db->arrayQuery( $query );

        return array( 'result' => $rows);
    }
}
