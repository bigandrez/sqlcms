function field_edit_click(element){
  if (element.getAttribute('id')=='fieldedit' || element.classList.contains('process')  || element.classList.contains('error')) return true;

  var oel = document.getElementById('fieldedit');
  if (oel){
    // В ответ на это событие должно "завершиться" редактирование предыдущего атрибута
    oel.children[0].onblur();
    if (oel = document.getElementById('fieldedit'))
      oel.removeAttribute('id','fieldedit');
  }

  element.setAttribute('id','fieldedit');

  var real_value;
  if (element.nextElementSibling && element.nextElementSibling.classList.contains('fieldvalue'))
    real_value = element.nextElementSibling.innerHTML;
  else
    real_value = element.innerHTML;
  element.old_real_value = real_value;

  try{
    element.classList.add('process');
    window[element.attributes.script.value](real_value,element,function(show_value, real_value){
      var element = document.getElementById('fieldedit');
      element.removeAttribute('id','fieldedit');

      if (element.old_real_value != real_value){
        setTimeout(save_field,0,element,real_value, element.getAttribute('href'));
        if (show_value == real_value){
          if (element.nextElementSibling && element.nextElementSibling.classList.contains('fieldvalue')){
            element.nextElementSibling.remove();
          }
        } else {
          var valel;
          if (element.nextElementSibling && element.nextElementSibling.classList.contains('fieldvalue')){
            valel = element.nextElementSibling;
          } else {
            valel = document.createElement('div');
            valel.classList.add('fieldvalue');
            element.parentElement.appendChild(valel);
          }
          valel.innerHTML = real_value;
        }
      } else
        element.classList.remove('process');

//      alert('COMPLETE');
    });
  } catch{
      alert('error');
  }

//  alert(value);
}

function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}

function save_field(element,real_value,href){
 
  var xhr = new XMLHttpRequest();
  xhr.element = element;
  xhr.open('POST', href, true);

  var data = {};
  data.real_value = real_value;
  var json = JSON.stringify(data);

  xhr.onreadystatechange = function(ret) {
    if (this.readyState != 4) return;
    this.element.classList.remove('process');
    var loc = xhr.getResponseHeader("Location");
    if (loc) {
      window.location = loc;
      return;
    }
    if (xhr.status != 200) {
      this.element.classList.add('error');
      this.element.setAttribute('title','Ошибка сохранения данных. Обновите страницу');
    } else {
    }
  }
  xhr.send(json);
}