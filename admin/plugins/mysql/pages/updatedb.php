<?php

function update_all_db_procedures(){
  update_table_to_json_proc();
  update_relations_for_node();
  update_nodeext_to_json_proc();

}

function update_nodeext_to_json_proc(){
global $db;
$crlf = '
';

  $t = $db->getAll("SELECT information_schema.tables.table_name FROM information_schema.tables where (information_schema.tables.table_name like 'nodeext_%') AND information_schema.tables.TABLE_SCHEMA=database();");

  $req = "CREATE DEFINER=`root`@`%` PROCEDURE `_nodeext_to_json_proc`(IN `node_id` INT,	OUT `json` LONGTEXT)  LANGUAGE SQL DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER COMMENT '' BEGIN".$crlf;
  $req .="DECLARE ret LONGTEXT default '';".$crlf;
  $req .="#call _create_settings('');".$crlf;
  $req .="set json:='';".$crlf;

  foreach($t as $v){
   $req .="call _table_to_json_proc(1,node_id,'".$v['table_name']."',ret); ";
//   $req .="if (json<>'') THEN set json:=CONCAT(json,','); END IF; ";
   $req .="set json := CONCAT(json, IF(json<>'',',',''), '\"".$v['table_name']."\":',IFNULL(ret,'null'));".$crlf;
  }

  $req .=" set json:=CONCAT('{',json,'}'); END";
//print $req;exit(0);
  $db->query("DROP PROCEDURE IF EXISTS `_nodeext_to_json_proc`");
  $db->query($req);
}

function update_relations_for_node(){
global $db;
$crlf = '
';


  $t = $db->getAll("
    SELECT information_schema.tables.table_name,  information_schema.columns.column_name FROM information_schema.tables
    left join information_schema.columns on information_schema.columns.table_name=information_schema.tables.table_name
    where (information_schema.columns.column_name like 'node\_%') AND information_schema.tables.TABLE_SCHEMA=database();
  ");

  
  foreach($t as $v){
    $req .= ($req ? "union".$crlf:'')."select nid from ".$v['table_name']." where ".$v['column_name']."=node_id".$crlf;
  }
  $req = "CREATE DEFINER=`root`@`%` FUNCTION `_relations_for_node`(`recursion` INT,`node_id` INT) RETURNS text CHARSET utf8 LANGUAGE SQL DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER COMMENT '' BEGIN ".$crlf.
         "DECLARE ret TEXT;
IF (_get_param('relation_enable','0')='0' OR (_get_param('relation_root_only','0')<>'0' AND recursion>0)) THEN return ''; END IF;
select CONCAT(',\"#relation\":[',IFNULL(group_concat(nid separator ','),''),']') from (".$crlf.$req.") a into ret; return ret; END";

  $db->query("DROP FUNCTION IF EXISTS `_relations_for_node`");
  $db->query($req);
}

function getjs(&$table_struct, $table_name, $main=FALSE){
  $res = "SELECT IFNULL(CONCAT('{',group_concat(subtable.gr separator '},{'),'}'),'null') ".($main?"into @json ":"")."from ( SELECT group_concat(concat('\"id\":',id";

  foreach($table_struct[$table_name] as $i=>$v){
    if (substr($i,0,6)=='table_'){
      $res.= sprintf(",',\"%s\":[',(".getjs($table_struct,$i,FALSE)."),']'",$i,$i);
      continue;
    }
    $res.= sprintf(",',\"%s\":',_to_json_value(%s)",$i,$i);
  }
  $res.=") separator '},{') as gr from ".$table_name." where nid=node_id ".(isset($table_struct[$table_name]['weight'])? 'group by weight order by weight asc':'').") subtable".($main?";":"");

  return $res;
}

function update_table_to_json(){
global $db;


  $t = $db->getAll("
    SELECT information_schema.tables.table_name,  information_schema.columns.column_name,  information_schema.columns.column_type FROM information_schema.tables
    left join information_schema.columns on information_schema.columns.table_name=information_schema.tables.table_name
    where (information_schema.tables.table_name like 'table\_%' OR information_schema.tables.table_name like 'node\_%') AND information_schema.tables.TABLE_SCHEMA=database();
  ");

  $table_struct=array();

  foreach($t as $v){
    if ($v['column_name']=='id') continue;
    $table_struct[$v['table_name']][$v['column_name']]=$v['column_type'];
  }
  unset($t);


  $req = "CREATE DEFINER=`root`@`%` FUNCTION `_table_to_json`(`node_id` INT,`table_name` TEXT) RETURNS longtext CHARSET utf8 LANGUAGE SQL DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER COMMENT ''
";

  $req .= 'BEGIN
  SET group_concat_max_len =16384*4;
';

  foreach($table_struct as $i=>$v){
    $req.="IF (table_name='".$i."') THEN
";
    $req.=getjs($table_struct,$i,TRUE)."
";
    $req.="END IF;
";
  }
    $req.="return @json;
END";

//print $req;
  $db->query("DROP FUNCTION IF EXISTS `_table_to_json`");
  $db->query($req);

//print_r($table_struct);
}


function get_simple_load(&$table_struct, $table_name){
  $res = "SELECT IFNULL(CONCAT('{',group_concat(subtable.gr separator '},{'),'}'),'null') into json from ( SELECT group_concat(concat('\"id\":',id";

  foreach($table_struct[$table_name] as $i=>$v){
    $res.= sprintf(",',\"%s\":',_to_json_value(%s)",$i,$i);
  }
  $res.=") separator '},{') as gr from ".$table_name." where nid=node_id ".(isset($table_struct[$table_name]['weight'])? 'group by weight order by weight asc':'').") subtable;";

  return $res;
}

function update_table_to_json_proc(){
global $db;
$crlf = '
';


  $t = $db->getAll("
    SELECT information_schema.tables.table_name,  information_schema.columns.column_name,  information_schema.columns.column_type FROM information_schema.tables
    left join information_schema.columns on information_schema.columns.table_name=information_schema.tables.table_name
    where (information_schema.tables.table_name like 'table\_%' OR information_schema.tables.table_name like 'node\_%' OR information_schema.tables.table_name like 'nodeext\_%') AND information_schema.tables.TABLE_SCHEMA=database();
  ");

  $table_struct=array();

  foreach($t as $v){
    if ($v['column_name']=='id') continue;
    $table_struct[$v['table_name']][$v['column_name']]=$v['column_type'];
  }
  unset($t);

  $maxvars=1;
  foreach($table_struct as $v) $maxvars = $maxvars>1 + count($v) ? $maxvars : 1 + count($v);


  $req.= 'CREATE DEFINER=`root`@`%` PROCEDURE `_table_to_json_proc`(IN `recursion` INT,IN `node_id` INT,IN `table_name` TEXT,OUT `json` LONGTEXT) LANGUAGE SQL NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER COMMENT \'\''.$crlf;

  $req.= 'BEGIN'.$crlf;
  for ($i=1;$i<$maxvars+1;$i++)
    $req.= 'DECLARE var'.$i.' TEXT;';
  $req .= $crlf;
  $req.= "DECLARE done integer default 0;".$crlf;
  $req.= "DECLARE arraysize integer;".$crlf;
  foreach($table_struct as $i=>$v) if (substr($i,0,6)=='table_'){
    $req.= "DECLARE ".$i."_limit integer default _get_row_limit('".$i."');".$crlf;
    $req.= "DECLARE ".$i."_offset integer default _get_row_offset('".$i."');".$crlf;
  }
  foreach($table_struct as $i=>$v) {
    $req.= 'DECLARE '.$i.'_cur cursor for select * from '.$i.' where nid=node_id';
    if (substr($i,0,6)=='table_')
      $req.= ' limit '.$i.'_limit offset '.$i.'_offset';
    if (substr($i,0,8)=='nodeext_')
      $req.= ' or nid is null order by nid desc limit 1';
    $req.= ';'.$crlf;
  }
  $req.= "DECLARE continue handler for sqlstate '02000' set done = 1;".$crlf;
  $req.= "SET group_concat_max_len =16384*4;".$crlf;

  foreach($table_struct as $i=>$v) {
    $req.= "IF (table_name='".$i."') THEN".$crlf;
    $is_simple=TRUE;
    foreach($v as $ii=>$vv)
      if (substr($ii,0,5)=='node_' || substr($ii,0,6)=='table_' || substr($ii,0,8)=='sqlnode_' || substr($ii,0,4)=='sql_' || substr($ii,0,8)=='nodeext_')
        $is_simple=FALSE;
    if ($is_simple){
        if (substr($i,0,6)=='table_') {
          $req.="  select count(*) into arraysize from ".$i." where nid=node_id;".$crlf;
        }
      $req.="  SELECT CONCAT('{',group_concat(subtable.gr separator '},{'),'}') into json from ( SELECT group_concat(concat('\"id\":',id";

      foreach($table_struct[$i] as $iii=>$vii)
        $req.= sprintf(",',\"%s\":',_to_json_value(%s)",$iii,$iii);
      $req.=") separator '},{') as gr from (select * from ".$i." where nid=node_id";
      if (substr($i,0,8)!='nodeext_') 
        $req.=" order by weight asc, id asc";
      if (substr($i,0,6)=='table_') 
        $req.=" limit ".$i."_limit offset ".$i."_offset";
      $req.=") tbl ".(isset($table_struct[$i]['weight'])? 'group by weight order by weight asc':'').") subtable;".$crlf;
      $req.="  IF (json is null) THEN SET json:=''; ".$crlf;
      if (substr($i,0,6)=='table_') 
        $req.="  ELSE SET json:=CONCAT('{\"#page\":',TRUNCATE(".$i."_offset/".$i."_limit,0),',\"#limit\":',".$i."_limit,',\"#offset\":',".$i."_offset,',\"#count\":',arraysize,'},',json);".$crlf;
      $req.="  END IF;".$crlf;

    } else {
        if (substr($i,0,6)=='table_') {
          $req.= "  select count(*) into arraysize from ".$i." where nid=node_id;".$crlf;
        }
      $req.= "  set json='';".$crlf;
      $req.= "  open ".$i."_cur;".$crlf;
      $req.= "  CLOOP: WHILE 2>1 DO".$crlf;
      $req.= "    FETCH ".$i."_cur into ";
      for ($j=1;$j<2+count($v);$j++) $req.=($j>1?',':'').'var'.$j;
      $req.= ";".$crlf;
      $req.= "    IF (done) THEN LEAVE CLOOP; END IF;".$crlf;
      $req.= "    IF (json<>'') THEN SET json=CONCAT(json,','); END IF;".$crlf;
      $j=2;
      foreach($v as $ii=>$vv){
        if (substr($ii,0,5)=='node_')
          $req.= "    call _node_to_json_proc(recursion+1,NULL, var".$j.",@t);".$crlf;
        if (substr($ii,0,6)=='table_')
          $req.= "    call _table_to_json_proc(recursion,node_id,'".$ii."',var".$j.");".$crlf;
        if (substr($ii,0,8)=='sqlnode_'){
          $req.= "    IF (_get_param('sqlnode_enable','0')<>'0') THEN".$crlf;
          $req.= "      call _sqlnode_to_json_proc(recursion,null,var".$j.",var".$j.");".$crlf;
          $req.= "      set var".$j.":=CONCAT('[',var".$j.",']');".$crlf;
          $req.= "    ELSE".$crlf;
          $req.= "      set var".$j.":=_to_json_value(var".$j.");".$crlf;
          $req.= "    END IF;".$crlf;
        }

//          $req.= "    call _sqlnode_to_json_proc(recursion,null,var".$j.",var".$j.");".$crlf;
        if (substr($ii,0,4)=='sql_')
          $req.= "    call _sql_to_json_proc(var".$j.",var".$j.");".$crlf;
        $j++;
      }
  
      $jtext = "'\"id\":',var1";
      if(substr($i,0,8)=="nodeext_")
        $jtext="";

      $j=2;
      foreach($v as $ii=>$vv){
        if ($ii=='nid' && substr($i,0,8)=='nodeext_'){$j++;continue;}
        if (substr($ii,0,6)=='table_')
          $jtext.= ($jtext?',",",':'')."'\"".$ii."\":[',var".$j.",']'";
        elseif (substr($ii,0,5)=='node_')
          $jtext.= ($jtext?',",",':'')."'\"".$ii."\":',var".$j;
        elseif (substr($ii,0,8)=='sqlnode_')
          $jtext.= ($jtext?',",",':'')."'\"".$ii."\":',var".$j;
        elseif (substr($ii,0,4)=='sql_')
          $jtext.= ($jtext?',",",':'')."'\"".$ii."\":',var".$j;
        else
          $jtext.= ($jtext?',",",':'')."'\"".$ii."\":',_to_json_value(var".$j.")";
        $j++;
      }

//print '!!!'.$jtext.'!!!';

      $req.= "    SET json:= CONCAT(json, '{',".$jtext.",'}');".$crlf;
      $req.= "  END WHILE;".$crlf;
      $req.= "  close ".$i."_cur;".$crlf;
      if (substr($i,0,6)=='table_') {
        $req.="  IF (json is not null and json<>'') THEN ".$crlf;
        $req.="    SET json:=CONCAT('{\"#page\":',TRUNCATE(".$i."_offset/".$i."_limit,0),',\"#limit\":',".$i."_limit,',\"#offset\":',".$i."_offset,',\"#count\":',arraysize,'},',json);".$crlf;
        $req.="  END IF;".$crlf;
      }
    }


    $req.= "END IF;".$crlf;
  }
  $req .= "IF (json is null OR json='') THEN IF (LEFT(table_name,6)='table_') THEN SET json=''; ELSE SET json='null'; END IF;END IF;".$crlf;
  $req .= 'END';

//print $req;
  $db->query("DROP PROCEDURE IF EXISTS `_table_to_json_proc`;");
  $db->query($req);
}

function update_sqlnode_to_json(){
print 'походу эта функция не нужна';
exit(0);
return;
global $db;

  $t = $db->getAll("
    SELECT information_schema.tables.table_name,  information_schema.columns.column_name FROM information_schema.tables
    left join information_schema.columns on information_schema.columns.table_name=information_schema.tables.table_name
    where (information_schema.columns.column_name like 'sqlnode\_%') AND information_schema.tables.TABLE_SCHEMA=database();
  ");

  $req = '';
  foreach($t as $v){
    $r = $db->getAll('select '.$v['column_name'].' from '.$v['table_name']);

    foreach($r as $g){
      if (strtolower(substr($g[$v['column_name']],0,7))!='select ') continue;
      $req .="IF (request='".$g[$v['column_name']]."') THEN select CONCAT('[',group_concat(_node_to_json(nid) separator ','),']') into @json from node where nid in (".$g[$v['column_name']]."); return @json; END IF;
";

    }
  }

  $req = "CREATE DEFINER=`root`@`%` FUNCTION `_sqlnode_to_json`(`request` TEXT) RETURNS longtext CHARSET utf8 LANGUAGE SQL DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER COMMENT '' BEGIN
".$req."END";

  $db->query("DROP FUNCTION IF EXISTS `_sqlnode_to_json`");
  $db->query($req);

}

