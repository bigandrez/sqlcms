<!DOCTYPE html><html lang="ru"><head><?php foreach($headers as $v):?><?=$v;?><?php endforeach;?><?=$core->snippet('topless');?></head><body>
<?=$content;?>
<?php foreach($footers as $v):?><?=$v;?><?php endforeach;?></body></html>