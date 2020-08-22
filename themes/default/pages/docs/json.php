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
<h2>Формат JSON ответа при чтении ноды из БД</h2>
<p></p>
<p>Поля, начинающиеся с символа # являются "вычисляемыми" - то есть этих полей нет в исходных таблицах, а являются они результатами каких-либо вычислений.</p>


<table class="desc">
<tr><td>{</td><td></td></tr>



<tr><td class="l1">"nid": 154,</td><td>Идентификатор документа</td></tr>
<tr><td class="l1">"res": "node_category",</td><td>Тип ресурса</td></tr>
<tr><td class="l1">"title": "page title",</td><td>Заголовок документа</td></tr>
<tr><td class="l1">"content": "page content",</td><td>Содержимое документа</td></tr>
<tr><td class="l1">"teaser": "page teaser",</td><td>Анонс документа</td></tr>
<tr><td class="l1">"node_groups": 0,</td><td>Идентификатор документа группы прав, к которым текущий документ имеет отношение</td></tr>
<tr><td class="l1">"created": 1517218069,</td><td>Дата создания документа</td></tr>
<tr><td class="l1">"changed": 1517218075,</td><td>Дата изменения документа</td></tr>
<tr><td class="l1">"published": 1,</td><td>Статус публикации</td></tr>
<tr><td class="l1">"url_code": "/napitki",</td><td><a href="/docs/urladdress">Ссылка на страницу</a> без указания протокола и имени домена</td></tr>
<tr><td class="l1">"url": "/napitki",</td><td><a href="/docs/urladdress">Ссылка страницу</a> без указания протокола и имени домена</td></tr>
<tr><td class="l1">"#parents": "1,5,8",</td><td>Идентификаторы родительских документов</td></tr>
<tr><td class="l1">"owner": {</td><td>Краткие сведения о владелеце документа</td></tr>
<tr><td class="l2">"nid": 1,</td><td>Идентификатор документа</td></tr>
<tr><td class="l2">"res": "node_users",</td><td>Тип ресурса</td></tr>
<tr><td class="l2">"title": "admin",</td><td>Заголовок документа</td></tr>
<tr><td class="l2">"content": "admin account",</td><td>Содержимое документа</td></tr>
<tr><td class="l2">"created": 1517218069,</td><td>Дата создания документа</td></tr>
<tr><td class="l2">"changed": 1517218075,</td><td>Дата изменения документа</td></tr>
<tr><td class="l2">"node_groups": 0,</td><td>Идентификатор документа группы прав, к которым текущий документ имеет отношение</td></tr>
<tr><td class="l2">"published": 1,</td><td>Статус публикации</td></tr>
<tr><td class="l2">"url_code": null,</td><td>url адрес страницы без указания протокола и имени домена</td></tr>
<tr><td class="l2">"url": null,</td><td>url адрес страницы без указания протокола и имени домена</td></tr>
<tr><td class="l2">"#parents": "1,5,8",</td><td>Идентификаторы родительских документов</td></tr>
<tr><td class="l2">"owner": 0</td><td>Владелец документа</td></tr>
<tr><td class="l1">},</td><td></td></tr>
<tr><td class="l1">"node"{</td><td>Дополнительные данные документа из таблицы node_category</td></tr>
<tr><td class="l2">"id": 147,</td><td>Идентификатор строки таблицы node_category</td></tr>
<tr><td class="l2">"nid": 154,</td><td>Идентификатор документа, которому принадлежит строка</td></tr>
<tr><td class="l2">"weight": 0,</td><td>"Вес" строки</td></tr>
<tr><td class="l2">"name": "Кофе,какао,кофе микс",</td><td>Какое-то поле из таблицы node_category</td></tr>
<tr><td class="l2">"table_category": [</td><td>Поле из таблицы node_category, заполняемое из таблицы table_category</td></tr>
<tr><td class="l3">{</td><td></td></tr>
<tr><td class="l4">"#page": 0,</td><td>Страница</td></tr>
<tr><td class="l4">"#limit": 1,</td><td>Лимит загружаемых из таблицы table_category строк за раз</td></tr>
<tr><td class="l4">"#offset": 0,</td><td>Смещение загружаемых строк с начала таблицы table_category</td></tr>
<tr><td class="l4">"#count": 6</td><td>Количество строк в таблице table_category, принадлежащих этому документу</td></tr>
<tr><td class="l3">},{</td><td></td></tr>
<tr><td class="l4">"id": 2,</td><td>Идентификатор строки таблицы node_category</td></tr>
<tr><td class="l4">"nid": 154,</td><td>Идентификатор документа, которому принадлежит строка</td></tr>
<tr><td class="l4">"weight": 0,</td><td>"Вес" строки</td></tr>
<tr><td class="l4">"node_category": {</td><td>Поле из таблицы table_category, ссылающееся на другой документ типа node_category</td></tr>


<tr><td class="l5">"nid": 9,</td><td>Идентификатор документа</td></tr>
<tr><td class="l5">"res": "node_category",</td><td>Тип ресурса</td></tr>
<tr><td class="l5">"title": null,</td><td>Заголовок документа</td></tr>
<tr><td class="l5">"content": null,</td><td>Содержимое документа</td></tr>
<tr><td class="l5">"teaser": null,</td><td>Анонс документа</td></tr>
<tr><td class="l5">"created": 1517218069,</td><td>Дата создания документа</td></tr>
<tr><td class="l5">"changed": 1517218075,</td><td>Дата изменения документа</td></tr>
<tr><td class="l5">"node_groups": 0,</td><td>Идентификатор документа группы прав, к которым текущий документ имеет отношение</td></tr>
<tr><td class="l5">"published": 1,</td><td>Статус публикации</td></tr>
<tr><td class="l5">"url_code": "/coffee",</td><td>url адрес страницы без указания протокола и имени домена</td></tr>
<tr><td class="l5">"url": "/coffee",</td><td>url адрес страницы без указания протокола и имени домена</td></tr>
<tr><td class="l5">"#parents": "154",</td><td>Идентификаторы родительских документов</td></tr>
<tr><td class="l5">"owner": null</td><td>Краткие сведения о владелеце документа</td></tr>

<tr><td class="l5">"node"{</td><td>Дополнительные данные документа из таблицы node_category</td></tr>
<tr><td class="l6">"id": 2,</td><td>Идентификатор строки таблицы node_category</td></tr>
<tr><td class="l6">"nid": 9,</td><td>Идентификатор документа, которому принадлежит строка</td></tr>
<tr><td class="l6">"weight": 0,</td><td>"Вес" строки</td></tr>
<tr><td class="l6">"name": "Кофе",</td><td>Какое-то поле из таблицы node_category</td></tr>
<tr><td class="l6">"table_category": null</td><td>Поле из таблицы node_category, заполняемое из таблицы table_category</td></tr>
<tr><td class="l5">}</td><td>Дополнительные данные документа из таблицы node_category</td></tr>


<tr><td class="l3">}</td><td></td></tr>
<tr><td class="l2">],</td><td></td></tr>
<tr><td class="l1">},</td><td></td></tr>

<tr><td>}</td><td></td></tr>
</table>

</div>


