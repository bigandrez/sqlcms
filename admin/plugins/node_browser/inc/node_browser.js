$(document).ready(function(){


  $("#plugins3").jstree({
    "core" : {
        "check_callback" : function (operation, node, parent, position, more) {
            if (!node.original || !parent.original) return true;
            if(node.original.disablednd || parent.original.disablednd) {
                return false;
            }
            return true;
        },
        'data' : {
            'url' : function (node) {
                return '';
            },
            'data' : function (node) {
                if (!node.original)
                    return { 'nid' : node.id };
                if (node.original.nid && node.original.table)
                    return { 'nid' : node.original.nid, 'table' : node.original.table };
                if (node.original.nid)
                    return { 'nid' : node.original.nid };
                return { 'nid' : node.id };
            }
        }
    },
    "conditionalselect" : function (node, event) {
      return false;
    },

    "contextmenu":{         
        "items": function($node) {
            var tree = $("#plugins3").jstree(true);
            var ret = {
                "Create": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Create",
                    "action": function (obj) { 

                        var ref = $.jstree.reference(obj.reference);
                        parent = ref.get_node($node.parent);
                        sel = ref.get_selected();
                        pos=0;
                        if (sel.length>0){
                            sel=sel[0];
                            pos = $.inArray(sel, parent.children)+1;
                        }
                        var params = {
                            parent: parent.id,
                            position: pos,
                            action: 'create_node'
                         };
                        $.post('', params, function ( data, textStatus, jqXHR ){
                            var tree = $("#plugins3").jstree(true);
                            sel = tree.create_node(parent, {"id": data, "children":true},pos+1);
                            if(sel) {
                                tree.edit(sel);
                            }
                        });
                    },
                    "submenu":{}
                },
                "Rename": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Rename",
                    "action": function (obj) { 
                        tree.edit($node);
                    }
                },                         
                "Remove": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Remove",
                    "action": function (obj) { 
                        tree.delete_node($node);
                    }
                }
            };
            for (var i=0;i<node_types.length;i++){
                ret.Create.submenu[node_types[i]] = {
                    "separator_before": false,
                    "separator_after": false,
                    "label": node_types[i],
                    "id": $node.id,
                    "action": function (obj) { 
                        var params = {
                            parent: obj.item.id,
                            position: 0,
                            action: 'create_node'
                         };
                        $.post('', params, function ( data, textStatus, jqXHR ){
                            var tree = $("#plugins3").jstree(true);
                            tree.refresh(data);
                        });

                    }
                }
            }
            return ret;
        }
    },

    "node_customize": {
      "default": function(el, node) {
        if (!node) return;
        if (!node.original) return;
        if (!node.original.disablednd) {
          $(el).append('<div class="extdata">('+node.id+', '+node.original.node_type+')</div>')
          return;
        }
        if (node.original.value!=undefined) {
          $(el).append('<div class="value">'+node.original.value+'</div>')
          return;
        }
      }
    },

    "state" : { "key" : "demo2" },
    "plugins" : [ "dnd", "_conditionalselect", "node_customize", "contextmenu" ]
  }).bind('move_node.jstree', function(e, data) {
    var params = {
        nid: +data.node.id,
        old_parent: data.old_parent=='#'?0:data.old_parent,
        new_parent: data.parent=='#'?0:data.parent,
        old_position: +data.old_position,
        new_position: +data.position
    };
    _move(params);
    console.log('move_node params', params);
  });



});


// Перемещение 
function _move(params) {
    var data = $.extend(params, {
        action: 'move_node'
    });
 
    $.post('', data);
}