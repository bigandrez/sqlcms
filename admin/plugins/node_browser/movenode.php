<?php

global $core;
    $nid = $_POST['nid'];
    $newparent = 0 + $_POST['new_parent'];
    $newpos = 0 + $_POST['new_position'];

//print 'newparent='.$newparent.' newpos='.$newpos.' nid='.$nid.' ';

    $maxbefore = $curweight = 0;
    if ($newpos > 0) {
        $maxbefore = $core->db->getOne("select max(weight) from (select weight from node where parent=?i order by weight asc limit ?i) tabl", $newparent, $newpos);
        $minafter = $core->db->getOne("select min(weight) as minweight from node where parent=?i and nid<>?i and nid not in (select * from (select nid from node where parent=?i order by weight asc limit ?i) tabl)", 
            $newparent, $nid, $newparent, $newpos);
        $curweight = $maxbefore + 1;
    } else
        $minafter = $core->db->getOne("select min(weight) as minweight from node where nid<>?i and parent=?i order by weight", $nid, $newparent);



    if ($minafter<=$curweight){
        $shiftweight = $curweight - $minafter + 1;
//print $maxbefore.' '.$minafter.' '.$curweight.' '.$shiftweight;exit(0);
        if ($newpos > 0) {
            $core->db->query("update node set weight=weight+?i where parent=?i and nid not in (select * from (select nid from node where parent=?i order by weight asc limit ?i) tabl)", 
                $shiftweight, $newparent, $newparent, $newpos);
        } else
            $core->db->query("update node set weight=weight+?i where parent=?i", $shiftweight, $newparent);
    }

    $core->db->query("update node set weight=?i, parent=?i where nid=?i", $curweight, $newparent, $nid);

//print_r($_POST);