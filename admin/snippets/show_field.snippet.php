<?php
// $show_field[1] - ����� �� ���������� ���� � ��������� ����
// ���� ��������� ���� ������������ � ��������� - �� ���� ����� �������� � ���������� ��������������
// $show_field[2] - ������������� ������������� ������ (nid ���� ������� node � id �� ���� ������ �������)
// $show_field[3] - �������� url
// $show_field[4] - �������� ������������� �������
// $show_field[5] - [����������������] ����� "��������������" ���������
// ���
// $show_field[2] - ��� ���� (����� ����� �� show_field_*) - � ������� �������� ����������, �������� "datetime,text"
// $show_field[3] - ������ �� callback

global $core;

  $callback = $show_field[3];

//  if ($callback){
    $core->footers['show_field.css']='<link rel="stylesheet" type="text/css" href="'.$core->pathes['ADMIN_LINK'].'snippets/show_field.css">';
    $core->footers['show_field.js']='<script type="text/javascript" src="'.$core->pathes['ADMIN_LINK'].'snippets/show_field.js"></script>';
//  }

  //  �������� ���������� � ������������� ����
  $field_type = explode(',',$show_field[2]);

  if (is_array($field_type)) foreach($field_type as $f){
    if (is_file($core->snippets['show_field_'.$f])){
      $field_type=$f;
      break;
    }
  }

  if (substr($show_field[4],0,5)=='node_' && is_file($core->snippets['show_field_node_select']))
    $field_type='node_select';

  // ���� ������� ��� ��������� ����� ������ �� ������, �� �� ��������� ���������� "show_field_text"
  if (is_array($field_type) || !$field_type) $field_type='text';

  if (is_file($core->snippets['show_field_'.$field_type]))
    $r = $core->snippet('show_field_'.$field_type,$show_field[1], $show_field[2],  $show_field[3], $show_field[4]);
  else
    $r = $show_field[1];

//  $callback = $show_field[2] > 0 ? '?nodeedit='.$show_field[2].'&table='.$show_field[3].'&column='.$show_field[4] : $show_field[3];
  if ($callback)
    $ret = '<div class="fieldedit '.$field_type.'" onclick="field_edit_click(this)" script="edit_field_'.$field_type.'" href="'.$callback.'">';

  if (is_null($show_field[1])) $r = '<i class="null"></i>';
  elseif (!$show_field[1] && $show_field[1]!==0) $r = '<i class="empty"></i>';
  $ret .= $r;
  if ($callback)
    $ret .= '</div>';

  if ($callback)
    if ($r!==$show_field[1])
      $ret .= '<div style="display:none" class="fieldvalue">'.$show_field[1].'</div>';


  return $ret;
