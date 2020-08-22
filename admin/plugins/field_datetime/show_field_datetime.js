// srcdata - текстовая строка с данными - в том виде, в котором хранится в базе
// domelement - dom элемент, в пределах которого нужно расположить редактор
// func - функция, которую нужно выполнить по завершению редактирования.
function edit_field_datetime(real_value, domelement, func){
  domelement.innerHTML = '<input type="datetime-local" onblur="edit_field_datetime_blur()"/>';
  _text_field = domelement.children[0];
  _text_field.style.width='100%';
  _text_field.style.height='100%';

  var datetime; 
  if (real_value) datetime = new Date(parseInt(real_value*1000));

  datetime = convertUTCDateToLocalDate(datetime);

  if (datetime)
    _text_field.value = datetime.toISOString().substr(0,19);

  _text_field.focus();
  _text_field.click();
  _text_field.node_edit_callback = func;
//  var input = domelement
//  func();
}

function edit_field_datetime_blur(){
  var val = _text_field.value.substr(8,2)+'.'+_text_field.value.substr(5,2)+'.'+_text_field.value.substr(0,4)+' '+_text_field.value.substr(11,8);
  var realval = _text_field.value;

  realval = (new Date(realval)).getTime()/1000;// + realval.getTimezoneOffset() * 60000;

  var nec = _text_field.node_edit_callback;
  if (!val)
    val = '<i style="color:gray">empty</i>';
  _text_field.parentElement.innerHTML = val;
  nec(val,realval);
}

function convertUTCDateToLocalDate(date) {
    return new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate(),  date.getHours(), date.getMinutes(), date.getSeconds()));
}

function convertLocalDatetoUTCDate(date){
    return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(),  date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
}