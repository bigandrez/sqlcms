<?php
global $core;

if ($_GET['nid']){
    include 'getnode.php';
    return '';
}

if ($_POST['action'] == 'move_node'){
    include 'movenode.php';
    return '';
}
if ($_POST['action'] == 'create_node'){
    include 'createnode.php';
    return '';
}

$core->headers[]='<title>Node browser</title>';
$core->footers[]='<link rel="stylesheet" type="text/css" href="'.$core->pathes['EXEC_PAGE_LINK'].'inc/css.css">';
$core->footers['jstree.style.min.css']='<link rel="stylesheet" type="text/css" href="'.$core->pathes['EXEC_PAGE_LINK'].'inc/jstree.style.min.css">';

$core->footers['jquery.min.js']='<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>';
$core->footers['jstree.min.js']='<script type="text/javascript" src="'.$core->pathes['EXEC_PAGE_LINK'].'inc/jstree.min.js"></script>';
$core->footers['jstree-node-customize.js']='<script type="text/javascript" src="'.$core->pathes['EXEC_PAGE_LINK'].'inc/jstree-node-customize.js"></script>';
$core->footers['node_browser.js']='<script type="text/javascript" src="'.$core->pathes['EXEC_PAGE_LINK'].'inc/node_browser.js"></script>';


// чтоб заставить сниппет подключить нужные скрипты нужно "сделать вид", что собираешся работать с его полями
    $core->snippet('show_field', '', 'text', '#');
    $core->snippet('show_field', '', 'onoff', '#');
    $core->snippet('show_field', '', 'datetime', '#');
    $core->snippet('show_field', '', 'number', '#');
    $core->snippet('show_field', '', 'image', '#');

$crlf='
';

    $node_types = array();
    foreach($core->fields as $i=>$v)
        if (substr($i,0,5)=='node_' || $i=='node')
            $node_types[]=$i;

print '<script>node_types = '.json_encode($node_types).';</script>';
print '<script>filesleft = \'{"headers":[],"files":[{"path":"settings","title":"Settings","weight":"0"},{"path":"nodes","title":"Nodes","weight":"0"}]}\'</script>';
print '<div id="leftfiles"></div>';


    $fields_settings = $core->db->getAll("select * from fields_settings");
    print '<script>nodetypes = JSON.parse(\'[{"type":"node", "title":"Просто документ", "description":"Материал без указания типа"}';
    $rez = '';
    foreach($core->fields as $i=>$v){
        if (substr($i,0,5)!='node_') continue;
        $title = $desc = '';                     
        foreach($fields_settings as $j=>$w)
            if ($w['param']==$i) {
                $title=$w['title'];
                $desc=$w['description'];
            }
        print ',{"type":"'.$i.'", "title":"'.$title.'", "description":"'.$desc.'"}';
    }
    print $rez;
    print ']\');</script>';

?>

<style>

</style>

    <div id="plugins3" class="demo plugin-demo">
    </div>


<?



return 'default';