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

<h2>Ссылка на страницу</h2>
<p>Рассмотрим упрощенную структуру адреса страницы в интернете:<br/>
<span class="code">[тип протокола]://[доменное имя]/[ссылка на страницу]?[параметры]</span><br/>
где:
<ul>
<li>тип протокола - <span class="code">http</span> или <span class="code">https</span></li>
<li>доменное имя - например google.ru</li>
<li>ссылка на страницу - например <span class="code">/docs/urladdress</span></li>
<li>параметры - параметры отображения страницы, например для работы пейджера</li>
</ul>
</p>
<p>Таким образом, ссылкой на страницу, в рамках данного фреймворка является относительный адрес страницы относительно доменного имени сайта.
Данная ссылка хранится для каждого документа в таблице <span class="code">node</span>.
Упрощенно - при запросе страницы, фреймворк ищет совпадающую ссылку в таблице <span class="code">node</span> и выводит соответствующий документ.</p>
<p>Однако бывает необходимость, чтобы страница открывалась и в случае, когда указана часть ссылки. Для этого при хранении в конце ссылки добавляется символ %.
Таким образом, страница со ссылкой <span class="code">/docs/urladdress%</span> откроется и по ссылке <span class="code">/docs/urladdress/firststep</span>, и <span class="code">/docs/urladdress/anytext</span> и т.д. </p>
<p>В случае, если имеется два документа со ссылками <span class="code">/docs/urladdress%</span> и <span class="code">/docs/urladdress/anytext</span> соответственно, то будет использована более длинная ссылка, и откроется 
документ со ссылкой <span class="code">/docs/urladdress/anytext</span></p>
<p></p>

</div>

