<?php header('HTTP/1.0 404 Not Found');?><!DOCTYPE html><html lang="ru"><head><?php foreach($headers as $v):?><?=$v;?><?php endforeach;?><?=$core->snippet('topless');?></head><body>
page not found
<?php foreach($footers as $v):?><?=$v;?><?php endforeach;?></body></html>