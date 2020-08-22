<h1>Node manager - список доступных типов нод</h1>
<?php
global $core;
  // Получаем список доступных типов материалов
  $node_tables = $core->db->getAll("
    SELECT information_schema.tables.table_name, fields_settings.title FROM information_schema.tables
    left join fields_settings on fields_settings.param = information_schema.tables.table_name
    where (information_schema.tables.table_name like 'node\_%') AND information_schema.tables.TABLE_SCHEMA=database()
    order by information_schema.tables.table_name asc
  ");

//print_r($node_tables);exit(0);

  $nodetypes = array();
  foreach($node_tables as $v) {
    $count = $core->db->getAll("SELECT count(*) as count from node where res='".$v['table_name']."'");
    $nodetypes[$v['table_name']]=array(
      'count' => $count[0]['count'],
      'title' => $v['title']
    );
  }
  $num=0;
?>
<table class="nodetypes"><tr><td>№</td><td>Тип ноды</td><td>Количество</td><td>Описание</td></tr>
<?php foreach($nodetypes as $i=>$v):$num++;?>
<tr>
<td><?=$num?></td>
<td><a href="?node=<?=$i?>"><?=$i?></a></td>
<td><?=$v['count']?></td>
<td><?=$v['title']?></td>

<style>
.nodetypes td{
border:1px dashed gray;
padding:10px;
}
</style>

<?php endforeach; return 'default';