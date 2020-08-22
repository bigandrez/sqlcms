
//init_admin_menu_tabs();

function init_admin_menu_tabs(){
  var pages = document.getElementsByClassName('leftblock')[0].getElementsByClassName('pages')[0].children;
  var pagetabs = document.getElementsByClassName('leftblock')[0].getElementsByClassName('pagetabs')[0];
  var html = '';
  for (var i=0;i<pages.length;i++){
    var tabname = '';
    try{
      tabname = pages[i].getElementsByClassName('tabname')[0].innerText;
    }catch(e){}
    if (tabname == '') tabname = 'tab '+(i+1);
    html += '<div class="pagetab'+i+(i==0?' active':'')+'" onclick="admin_menu_tab_click('+i+')">'+tabname+'</div>';
  }
  pagetabs.innerHTML = html;
}

function admin_menu_tab_click(tabnum){
  var pages = document.getElementsByClassName('leftblock')[0].getElementsByClassName('pages')[0].children;
  var pagetabs = document.getElementsByClassName('leftblock')[0].getElementsByClassName('pagetabs')[0].children;
  for (var i=0;i<pages.length;i++){
    pages[i].style.setProperty('display','none');
  }
  for (var i=0;i<pagetabs.length;i++){
    pagetabs[i].classList.remove('active');
  }
  pagetabs[tabnum].classList.add('active');

  var el = document.getElementsByClassName('leftblock')[0].getElementsByClassName('pages')[0].children[tabnum];
  el.style.setProperty('display','block');
}