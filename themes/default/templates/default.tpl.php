<!DOCTYPE html><html lang="ru"><head><?php if (is_array($headers)) foreach($headers as $v):?><?=$v;?><?php endforeach;?><?=$core->snippet('topless');?></head><body>
<div class="body">
<div class="preheader"><div class="page"><div class="block"></div></div>
<div class="header"><div class="page"><div class="block logo">SQL JSON<br/>CMS</div></div></div></div>
<div class="content"><?=$content;?></div>
<div class="footer"><div class="page"><div class="block"></div></div></div>
<div class="postfooter"><div class="page"><div class="block"></div></div></div>
</div>
<?php foreach($footers as $v):?><?=$v;?><?php endforeach;?></body></html>