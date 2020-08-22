<h1>Лог запросов к БД</h1>
<?php
$headers[]='<title>Лог запросов к БД</title>';
print 'Signals:<br/>';
global $signals;
print '<pre>';
print_r($signals);
print '</pre>';

print 'Snippets:<br/>';
global $snippets;
print '<pre>';
print_r($snippets);
print '</pre>';

print 'Templates:<br/>';
global $templates;
print '<pre>';
print_r($templates);
print '</pre>';

print 'Pages:<br/>';
global $pages;
print '<pre>';
print_r($pages);
print '</pre>';

print 'Settings:<br/>';
global $settings;
print '<pre>';
print_r($settings);
print '</pre>';

return 'default';