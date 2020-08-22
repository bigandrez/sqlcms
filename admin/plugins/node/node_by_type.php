<h1>Node manager - список нод типа <?=$_GET['node']?></h1>
<?php
global $core;
  $nodetype = $_GET['node'];


  $core->db->sql_load($nodes_by_type, "select * from node where res='".$nodetype."'", "select count(*) from node where res='".$nodetype."'");

  unset($nodes_by_type[0]);

//print_r($nodes_by_type);exit(0);
global $pager;
  $num=0+$pager['#offset'];
?>
<style>
.nodetypes td{
border:1px dashed gray;
padding:10px;
}
</style>
<table class="nodetypes"><tr><td>№</td><td>nid</td><td>Заголовок</td></tr>
<?php foreach($nodes_by_type as $i=>$v):$num++;?>
<tr onclick="location.href='?node=<?=$v['nid']?>'">
<td><?=$num?></td>
<td><?=$v['nid']?></td>
<td><?=$v['title']?></td>
<?php endforeach;?>
</table>
<?php 
return 'default';