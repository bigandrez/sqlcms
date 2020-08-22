<style>
bbody{color:black;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:18px;}
.bbody{margin:0 auto;width:1280px;}
table.desc{}
table.desc tr:hover{background-color: #ddddff!important;}
table.desc tr:nth-child(even){background-color: #f8f8f8;}
table.desc td{padding:0 5px;}
table.desc td.l1{padding:0 5px 0 30px;}
table.desc td.l2{padding:0 5px 0 60px;}
table.desc td.l3{padding:0 5px 0 90px;}
table.desc td.l4{padding:0 5px 0 120px;}
table.desc td.l5{padding:0 5px 0 150px;}
table.desc td.l6{padding:0 5px 0 180px;}
table.desc td.l7{padding:0 5px 0 210px;}
table.desc td.l8{padding:0 5px 0 240px;}
table.desc td.l9{padding:0 5px 0 270px;}
table.desc td:first-child{font-family:monospace;vertical-align: text-top;}
table.desc td:last-child{}
.code{font-family:monospace;}

</style>
</head>
<bbody>

<div class="bbody">

<h1>SQL JSON CMF</h1>
version 1.0
<h2>Передача параметров в SQL процедуру загрузки ноды</h2>
<p>Поскольку основная работа по загрузке ноды ложится на sql процедуру - то возникает необходимость передать какие-либо параметры в эту процедуру.
Как пример - загрузить первые 10 строк из таблицы <span class="code">table_roles</span>, но последние 10 строк таблицы <span class="code">table_groups</span> - которая являелся "вложенной" по отношению к <span class="code">table_roles</span>. </p>
<p>
Реализовано это следующим образом: со стороны php в sql процедуру <span class="code">_node_to_json_proc</span> передается строка вида<br/>
<span class="code">"table_rights.row_limit=10;table_user.page=2"</span></p>
<p>
Строка напрямую передается в sql процедуру загрузки ноды, где будет преобразована в таблицу <span class="code">tempsettings</span> с полями <span class="code">parameter</span> и <span class="code">value</span>, 
с предварительной загрузкой в эту таблицу другой таблицы - <span class="code">settings</span>, имеющую те же поля.</p>
<p>
Шаги следующие:
<ul>
<li>Создается временная (живущая только в пределах сессии) таблица <span class="code">tempsettings</span> с полями <span class="code">parameter</span> и <span class="code">value</span></li>
<li>В таблицу <span class="code">tempsettings</span> копируются данные из таблицы <span class="code">settings</span> с такими же полями</li>
<li>В таблицу <span class="code">tempsettings</span> "распаковываются" данные из переданной строки. Если появляются "дублирующиеся" параметры - то они заменяются.</li>
</ul>
</p>
<p>Все, вызываемые впоследствии sql процедуры, имеют доступ к таблице <span class="code">tempsettings</span>, из которой и могут достать соответствующие параметры, переданные из php.
</p>
<p>
Используемые ядром на настоящий момент параметры:
<ul>
<li><span class="code">limit</span> - ограничение на загружаемое количество строк изо всех таблиц вида <span class="code">table_*</span></li>
<li><span class="code">offset</span> - пропуск указанного количество строк во всех таблиц вида <span class="code">table_*</span></li>
<li><span class="code">page</span> - пропуск вычисляемого как <span class="code">limit*page</span> строк во всех таблиц вида <span class="code">table_*</span></li>
<li><span class="code">table_user.limit, table_user.offset, table_user.page</span> - то же самое, но для конкретно указанной таблицы</li>
</ul>
</p>


</div>
