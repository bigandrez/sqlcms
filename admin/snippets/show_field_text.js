var _text_field = null;

// srcdata - текстовая строка с данными - в том виде, в котором хранится в базе
// domelement - dom элемент, в пределах которого нужно расположить редактор
// func - функция, которую нужно выполнить по завершению редактирования.
function edit_field_text(real_value, domelement, func){
  var width=domelement.clientWidth;
  var height=domelement.clientHeight;
  var rect = domelement.getBoundingClientRect();
  var padding_left = parseInt(window.getComputedStyle(domelement, null).getPropertyValue('padding-left'))
  var padding_top = parseInt(window.getComputedStyle(domelement, null).getPropertyValue('padding-top'))
  var padding_right = parseInt(window.getComputedStyle(domelement, null).getPropertyValue('padding-right'))
  var padding_bottom = parseInt(window.getComputedStyle(domelement, null).getPropertyValue('padding-bottom'))

  _text_field = document.createElement('textarea');
  _text_field.onblur=edit_field_text_blur;

//  _text_field = domelement.children[0];
  _text_field.style.width=(width-padding_left-padding_right)+'px';
  _text_field.style.height=(height-padding_top-padding_bottom)+'px';
  _text_field.style.position="absolute";
  _text_field.style.top=(rect.top+window.pageYOffset)+'px';
  _text_field.style.left=rect.left+'px';
  _text_field.style.padding=padding_top+"px "+padding_right+"px "+padding_bottom+"px "+padding_left+"px";

  domelement.appendChild(_text_field);

  _text_field.innerHTML = real_value;
  _text_field.focus();
  _text_field.click();
  _text_field.node_edit_callback = func;
//  var input = domelement
//  func();
}

function edit_field_text_blur(){
  var val = _text_field.value;
  var realval = _text_field.value;
  var nec = _text_field.node_edit_callback;
  if (!val)
    val = '<i style="color:gray">empty</i>';
  _text_field.parentElement.innerHTML = val;
  nec(val,realval);
}