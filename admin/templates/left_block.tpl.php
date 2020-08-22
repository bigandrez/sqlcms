<?php 
foreach($pagen as $v):?>
<div class="item">
<?php if ($v['settings']):?>
<div class="settings" onclick="location.href='<?=$v['settings']?>'"></div>
<?php endif;?>
<div class="title"><?=$v['title']?></div>
<?php if (is_array($v['pages'])) foreach($v['pages'] as $page):?>
<div class="pagelink"><a href="<?=$page['link']?>"><?=$page['name']?></a></div>
<?php endforeach;?>
</div>
<?php endforeach;?>
