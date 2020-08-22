<!DOCTYPE html><html lang="ru"><head><?php foreach($core->headers as $v):?><?=$v;?><?php endforeach;?><?=$core->snippet('topless');?></head><body>
<div class="body">
<div class="preheader"><div class="page"><div class="block"></div></div>
<div class="header"><div class="page"></div></div></div>
<div class="leftblock">
  <div class="block logout"><a href="<?=$_SERVER['ADMIN_LINK']?>?logout">Выход</a></div><div class="block logo">SQL JSON<br/>CMS</div>
  <div class="pagetabs"></div>
  <div class="pages">
    <div><div class="tabname">Настройки</div><?=$core->snippet('left_block');?></div>
    <div><div class="tabname">Данные</div>qqq<?=$core->snippet('left_block2');?></div>
    <div>qqq2<?=$core->snippet('left_block2');?></div>
  </div>
</div>
<div class="content"><?=$content;?><?=$core->snippet('pager');?></div>
<div class="footer"><div class="page"><div class="block"></div></div></div>
<div class="postfooter"><div class="page"><div class="block"></div></div></div>
</div>
<?php foreach($core->footers as $v):?><?=$v;?><?php endforeach;?></body></html>