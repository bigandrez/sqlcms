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
<h2>Структура базы данных</h2>
<p></p>
<p>Основой структуры является таблица документов <span class="code">node</span>. В этой таблице есть 2 ключевых поля: идентификатор документа <span class="code">nid</span> и тип документа - поле <span class="code">res</span>.</p>
<p>Тип документа представляет собой фразу "<span class="code">node_*</span>" (где * - любой текст маленькими английскими буквами и цифры) и является названием таблицы, содержащей дополнительные поля для документа.
Например <span class="code">node_users</span> - таблица, описывающая пользователей.</p>
<p>Любая таблица документа (например <span class="code">node_users</span>) содержит поле <span class="code">nid</span>, в соответствии с которым производится выборка строк, соответствующих заданному документу.</p>
<p>Таблица документа <span class="code">node_*</span> в обязательном порядке должна содержать следующие поля:
<ul>
<li><span class="code">id</span> (числовое) - идентификатор строки</li>
<li><span class="code">nid</span> (числовое) - идентификатор документа</li>
<li><span class="code">weight</span> (числовое) - "вес" строки (для сортировки)</li>
</ul>
</p>
<p>Таблица документа <span class="code">node_*</span> может содержать и любые другие поля, требуемые для представления документа.</p>
<h3>Поле <span class="code">node_*</span></h3>
<p>Для создания поля, содержащего указание на другой документ - может быть создано такое же числовое поле <span class="code">node_*</span>, содержащее идентификатор другого документа. При загрузке основного документа,
в результирующем json в содержимое этого поля будет подставлена полная структура указанного документа из указанной названием поля таблицы БД.<br/>
Внимание! Если содержимое поля является идентификатором "родительского" документа, то может возникнуть "вечная рекурсия".</p>

<h3>Поле <span class="code">table_*</span></h3>
<p>Используется для хранения множества строк, принадлежащих одному документу. Название поля должно соответствовать такому же названию таблицы в БД. Таблица <span class="code">table_*</span>, так же как и 
<span class="code">node_*</span> обязательно должна содержать в своем составе поля <span class="code">id</span>, <span class="code">nid</span> и <span class="code">weight</span>. Также, данная таблица
может содержать и любые другие поля, по аналогии с <span class="code">node_*</span>. Например, таблица <span class="code">table_*</span> может содержать поле <span class="code">node_*</span> -
посредством чего возможно "привязать" к одному документу ссылки на множество других.
</p>

<h3>Поле <span class="code">sqlnode_*</span></h3>
<p>Поле может содержать SQL запрос, возвращающий список идентификаторов документов (например <span class="code">select nid from node where res='node_users'</span>). 
Все документы с указанными идентификаторами, при загрузке, будут автоматически загружены в это поле.
Таким образом, возможно, к примеру, создание документа, подгружающего в указанное поле описание всех пользователей.<br/>
Внимание! Если содержимое поля является идентификатором "родительского" документа, то может возникнуть "вечная рекурсия".</p>

<h3>Поле <span class="code">sql_*</span></h3>
<p>Поле позволяет выполнить любой указанный SQL запрос с загрузкой всех результатов в это поле.</p>


</div>
