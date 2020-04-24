<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (Schema::hasColumn('codo_tags', 'cat_id')) {

    Schema::table('codo_tags', function($table) {
        $table->dropColumn('cat_id');
    });
}

$permission = new \CODOF\Permission\Permission();
$permission->add('move topics', 'forum');


/*
 * 
 * plg_schema_ver to varchar(50)
 */
