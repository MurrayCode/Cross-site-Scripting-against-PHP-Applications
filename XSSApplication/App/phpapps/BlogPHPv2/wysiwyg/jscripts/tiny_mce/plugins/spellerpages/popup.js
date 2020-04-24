var tinyMCE = null, tinyMCELang = null;
var win = window.opener ? window.opener : window.dialogArguments;

if (!win)
  win = top;

window.opener = win;
this.windowOpener = win;

// Setup parent references
tinyMCE = win.tinyMCE;
tinyMCELang = win.tinyMCELang;
      
if (!tinyMCE) {
  alert("tinyMCE object reference not found from popup.");
 }
 else {
this.isWindow = tinyMCE.getWindowArg('mce_inside_iframe', false) == false;
this.storeSelection = tinyMCE.isMSIE && !this.isWindow && tinyMCE.getWindowArg('mce_store_selection', true);

// Store selection
if (this.storeSelection)
  tinyMCE.selectedInstance.execCommand('mceStoreSelection');

// Setup dir
if (tinyMCELang['lang_dir'])
  document.dir = tinyMCELang['lang_dir'];


// Output Popup CSS class
document.write('<link href="' + tinyMCE.getParam("popups_css") + '" rel="stylesheet" type="text/css">');
 }


function translateNode(node) {
  //  visitNode('node:'+node);
  visitNode(node);
  children = node.childNodes;
  traverseChildren(children,children.length-1);
}


function traverseChildren(children,i) {
  if (i<0) {
    return;
  }
  node = children[i];
  visitNode(node);
  childs = node.childNodes;
  traverseChildren(childs, childs.length-1);
  return traverseChildren(children,i-1);
}

function visitNode(node) {
  //  alert(node);
  //  alert(node.nodeName+':'+node.nodeValue);
  translateAttributes(node);
  translateInnerHTML(node);
}

function translateInnerHTML(node) {
  re = /({\$)([^}]+)(})/g;
  inner = node.nodeValue;
  if (inner) {
    try {
      inner = inner.replace(re, 
			    function(str,p1,p2,p3,offset,s) {
			      return tinyMCE.getLang(p2,0);
			    }
			    );
      if (inner!=node.nodeValue) {
	node.nodeValue = inner;
      }
    } 
    // msIE attributes does not support nodeValue
    catch(e) {
        inner = node.value;
	inner = inner.replace(re, 
			      function(str,p1,p2,p3,offset,s) {
			      return tinyMCE.getLang(p2,0);
			      }
			      );
	if (inner!=node.value) {
	  node.value = inner;
	}
    }
  }
}

function translateAttributes(node) {
  re = /({\$)([^}]+)(})/;
  attr = node.attributes;
  if (attr) {
    for ( j = 0; j < attr.length ; j++) {
      a = attr[j];
      translateInnerHTML(a);
    }
  }
  if (node.nodeType == 9) {
    node.title = node.title.replace(re, 
				    function(str,p1,p2,p3,offset,s) {
				      return tinyMCE.getLang(p2,0);
				    }
				    );
  }
}
