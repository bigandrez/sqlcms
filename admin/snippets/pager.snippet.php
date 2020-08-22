<?php
global $pager;
  if (!isset($pager['#page'])) return '';
  if ($pager['#count']<=$pager['#limit']) return '';

  $page_limit = 10; // Максимальное количество ссылок в пейджере

  $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

  $uri = '';
  foreach($_GET as $i=>$v){
    if ($i=='page') continue;
    $uri .= ($uri ? '&' : '') . $i . '=' . $v;
  }

  $uri = $uri_parts[0] . '?' . $uri . ($uri ? '&' : '');

  $p=0;$c=0; $curpage = 0+$_GET['page'];
  $ret = '<ul class="pager">';
  while (TRUE){
    if ($curpage>3){
      if ($p==1){
        $ret .= '<li><span>...</span></li>';
        $p = $curpage-1;
        $c++;
        continue;
      }
    }
    if ($p!=$curpage)
      $ret .= '<li><a href="'.$uri.'page='.$p.'">'.($p+1).'</a></li>';
    else
      $ret .= '<li><span>'.($p+1).'</span></li>';
    if ($c>=$page_limit-1 || $p>=$pager['#count']/$pager['#limit']-1)
      break;
    $p++;$c++;
  }
  if ($p<=$pager['#count']/$pager['#limit']-1)
    $ret .= '<li>...</li>';
  $ret .= '</ul>';

  return $ret;
