// real_value - текстовая строка с данными - в том виде, в котором хранится в базе
// domelement - dom элемент, в пределах которого нужно расположить редактор
// func - функция, которую нужно выполнить по завершению редактирования.
function edit_field_onoff(real_value, domelement, func){

  real_value = real_value === null || real_value === 0 || real_value === undefined || real_value === '' || real_value === '0' ? 1 : 0;
  var val = real_value ? '<i>true</i>' : '<i>false</i>';
  domelement.innerHTML = val;
  func(val,real_value);

}
