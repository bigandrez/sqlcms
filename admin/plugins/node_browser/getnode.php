<?php
global $core;

function node_show_snippet($v,$nid,$table,$column){
global $core;
  $fld =  $core->fields[$table][$column];
  $field_type = ''.$fld['userdata']['usertype'];
  if (!$field_type) $field_type='text';
  $callback = '?nodeedit='.$nid.'&table='.$table.'&column='.$column;
  return $core->snippet('show_field', $v, $field_type, $callback);
}



    header('Content-Type: application/json');
   
    if ($_GET['table']){
        $nid = 0+$_GET['nid'];
        $table = $_GET['table'];
// !!!!!! надо сделать
//        if (!check_table_column($table)) return;

        $data = $core->db->getAll("select * from ".$table." where nid=?i",$nid);

        $ret = array();
        foreach($data[0] as $i=>$v){
            if ($i=='id' || $i=='nid' || $i=='weight' || substr($i,0,6)=='table_') continue;
            $val = node_show_snippet($v,$nid,$table,$i);
            if ($table=='node' && $i=='res') $val = $v;
            $ret[]=array("text"=>$i.':',"id"=>"","children"=>false,"icon"=>$core->pathes['EXEC_PAGE_LINK'].'inc/fields.png', "li_attr"=>array("class"=>"fieldvalue"), "disablednd"=>true, "value"=>''.$val);
        }

        print json_encode($ret);
        return;
    }



    $nid = ($_GET['nid']=='#') ? 0 : 0+$_GET['nid'];

    $res = $core->db->getAll("select title as text, nid as id, res as node_type from node where parent=".$nid." order by weight");

    foreach($res as $i=>$v) {
        $res[$i]['children'] = (bool)1;
        $res[$i]['nid'] = $v['id'];
    }

    if ($nid>0) {
        $res[]=array("text"=>"node","id"=>"node".$nid,"children"=>true,"icon"=>$core->pathes['EXEC_PAGE_LINK'].'inc/fields.png', "disablednd"=>true, "nid"=>$nid, "table"=>"node", "li_attr"=>array("class"=>"tablename"));
        $type = $core->db->getOne("select res from node where nid=?i",$nid);
        $res[]=array("text"=>$type,"id"=>$type.$nid,"children"=>true,"icon"=>$core->pathes['EXEC_PAGE_LINK'].'inc/fields.png', "disablednd"=>true, "nid"=>$nid, "table"=>$type, "li_attr"=>array("class"=>"tablename"));
    }
    print json_encode($res);


/*
?>[{"text":"Hello Doc","id":"eut8fsss","children":1,"state":{"selected":true}},{"text":"Foo","id":"ss3chev9","children":0}]
<?php

*/
