<h1>Global arrays</h1>
<?php
global $core;
$core->headers[]='<title>Global arrays</title>';
print '<pre>Signals: ';
print_r($core->signals);
print '</pre>';

print '<pre>Snippets: ';
print_r($core->snippets);
print '</pre>';

print '<pre>Templates: ';
print_r($core->templates);
print '</pre>';

print '<pre>Pages: ';
print_r($core->pages);
print '</pre>';

print '<pre>Settings: ';
print_r($core->settings);
print '</pre>';

return 'default';