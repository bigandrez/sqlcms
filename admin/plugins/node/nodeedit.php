<?php
global $core;
$core->headers[]='<title>Node add/view/edit/delete</title>';
$core->footers[]='<link rel="stylesheet" type="text/css" href="'.$core->pathes['EXEC_PAGE_LINK'].'inc/css.css">';
$core->footers[]='<script type="text/javascript" src="'.$core->pathes['EXEC_PAGE_LINK'].'inc/nodeedit.js"></script>';

$crlf='
';
  $page = array();
  if (!preg_match("/([^?\.]*)/", $_SERVER['REQUEST_URI'], $page)) return;
  $page = $page[0]=='/' ? '' : $page[0];

  $nodetypes = array('node_users', 'node_roles','node_groups','node_rights');
  $ret='';

  $nid = 0+is_numeric($_GET['node'])?$_GET['node']:0;

  if (!$nid) return 'default';
  $nodes = $core->db->node_load(0+$nid,array(),TRUE);
  $nodes = json_decode($nodes,TRUE);

  $owner = $nodes['nodes'][$nid]['owner'];
  $ownertitle = $nodes['nodes'][$owner]['title'];
  if (!isset($nodes['nodes'][$owner])){
    $ownertitle = '<i>user undefined</i>';
    $owner=0;
  }
  if (!$ownertitle) $ownertitle='';


function node_get_title($table, $column, $default){
global $core;
  return $core->fields[$table][$column]['userdata']['title'] ? $core->fields[$table][$column]['userdata']['title'] : $default;
}

function node_show_snippet($v,$nid,$table,$column){
global $core;
  $fld =  $core->fields[$table][$column];
  $field_type = ''.$fld['userdata']['usertype'];
  if (!$field_type) $field_type='text';
  $callback = '?nodeedit='.$nid.'&table='.$table.'&column='.$column;
  return $core->snippet('show_field', $v, $field_type, $callback);
}
//print_r($nodes);exit(0);
?>

<h1>Node add/view/edit/delete</h1>
<div>Node id <span class="nid"><?=$nid?></span>, type <span class="type"><?=$nodes['nodes'][$nid]['res']?></span>, owner 
<?= $owner ? '<a href="?node='.$owner.'">['.$owner.'] '.$ownertitle.'</a>' : '<i>undefined</i>'?></div>

<div class="item25">
<div class="item_title">Owner:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['owner'], $nid,'node','owner')?></div></div>
<div class="item25">
<div class="item_title"><?=node_get_title('node','created', 'Created')?>:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['created'], $nid,'node','created')?></div></div>
<div class="item25">
<div class="item_title"><?=node_get_title('node','changed','Changed')?>:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['changed'],$nid,'node','changed')?></div></div>
<div class="item25">
<div class="item_title"><?=node_get_title('node','published', 'Published')?>:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['published'],$nid,'node','published')?></div></div>


<div class="item">
<div class="item_title">Title:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['title'],$nid,'node','title')?></div></div>
<div class="item">
<div class="item_title">Teaser:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['teaser'],$nid,'node','teaser')?></div></div>
<div class="item">
<div class="item_title">Content:</div>
<div class="item_value"><?=node_show_snippet($nodes['nodes'][$nid]['content'], $nid,'node','content')?></div></div>

<?php if ($nodes['nodes'][$nid]['node']) foreach($nodes['nodes'][$nid]['node'] as $i=>$v): if (in_array($i,array('id','nid')) || is_array($v)) continue;?>
<div class="item">
<div class="item_title"><?=node_get_title($nodes['nodes'][$nid]['res'],$i,'Field '.$i)?>:</div>
<div class="item_value"><?=node_show_snippet($v,$nodes['nodes'][$nid]['node']['id'],$nodes['nodes'][$nid]['res'],$i)?></div></div>
<?php endforeach;?>

<?php if ($nodes['nodes'][$nid]['node']) foreach($nodes['nodes'][$nid]['node'] as $i=>$v): if (substr($i,0,6)!='table_') continue;?>
<div class="item">
<div class="item_title">Table <?=$i?>:</div>
<table>
  <tr>
    <?php $cc=0;if (isset($nodes['nodes'][$nid]['node'][$i][1])) foreach($nodes['nodes'][$nid]['node'][$i][1] as $i2=>$v2): if ($i2=='nid') continue;$cc++;?>
      <th><?=$i2?></th>
    <?php endforeach;?>
  </tr>

  <?php if (isset($nodes['nodes'][$nid]['node'][$i])) foreach($nodes['nodes'][$nid]['node'][$i] as $i2=>$v2): 
    // Пропускаем нулевой элемент - с информацией о таблице
    if (!$i2) continue;?>
    <tr>
      <?php foreach($nodes['nodes'][$nid]['node'][$i][$i2] as $column_name=>$column_value): if ($column_name=='nid') continue;?>
        <?php if ($column_name=='id'):?>
          <td style="max-width:<?=100/$cc?>px"><?=$column_value?></td>
        <?php else:?>
          <td style="max-width:<?=100/$cc?>px"><?=node_show_snippet($column_value,$nodes['nodes'][$nid]['node'][$i][$i2]['id'],$i,$column_name)?></td>
        <?php endif;?>
      <?php endforeach;?>
    </tr>
  <?php endforeach;?>
</table>
</div>
<?php endforeach;?>


<?php

/*
    print '<ul class="node">';
    foreach($nodes['nodes'][$nid] as $i=>$v):
      if ($i=='node') continue;
      $ed = in_array($i,array('nid','res')) ? '' : '<div class="edit"><a href="">edit</a></div>';
      if (is_null($v)) $v='<i>null</i>';
      print '<li><div class="column">'.$i.'</div><div class="value">'.$v.$ed.'</div></li>';
    endforeach;
    print '<li>node';

    print '<ul class="node">';
    foreach($nodes['nodes'][$nid]['node'] as $i=>$v):
      $ed = in_array($i,array('id','nid')) || is_array($v) ? '' : '<div class="edit"><a href="">edit</a></div>';
      if (!is_array($v))
        print '<li><div class="column">'.$i.'</div><div class="value">'.$v.$ed.'</div></li>';
      else {
        print '<li class="nos"><div class="column">'.$i.'</div>';
        print '<ul class="table">';
        unset($v[0]);
        foreach($v as $i2=>$v2):
          foreach($v2 as $i3=>$v3):
             $ed = in_array($i3,array('id','nid')) ? '' : '<div class="edit"><a href="">edit</a></div>';
            print '<li><div class="column">'.$i3.'</div><div class="value">'.$v3.$ed.'</div></li>';
          endforeach;
        endforeach;
        print '</ul>';
        print '</li>';
      }
    endforeach;
    print '</ul>';

    print '</li>';
    print '</ul>';
*/
?>
<?php return 'default';