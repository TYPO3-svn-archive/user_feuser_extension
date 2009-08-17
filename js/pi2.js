function cssDisplay(cells, press) {
	var test="->";

	for (var i = 0; i < cells.length; i++) { 
		className = cells[i].className;
		if (className != null) {
			if (className=='press') {
				if (press) {
					cells[i].style.display='block';
				} else {
					cells[i].style.display='none';
					
				}
			} else if (className=='public') {
				if (press) {
					cells[i].style.display='none';
					
					cells[i].checked=false;
					
				} else {
					cells[i].style.display='block';
				}
			}
		}
	}
	//alert(test);
};

function pressOrNotPress(me) {
	var press=false;
	var display='none';
	if (me.value==2) {press=true; display='block'};
	var cells = document.getElementsByTagName('input');
	for (var i = 0; i < cells.length; i++) { 
	   //doesn't work with ie->  className = cells[i].getAttribute("class"); 
	   className = cells[i].className
	     
	   // if (cells[i].tagName=='INPUT')  
	   
	   
	   if (className != null) {
	   		
	   		var tableau=className.split(' ');
	   		//alert(className+' '+tableau.length);
	   		if (tableau.length>1) {
	   			for (var j=1; j<tableau.length; j++) {
	   				if (tableau[j]=='press') {
	   					//alert(cells[i].parentNode.nodeName+":"+cells[i].parentNode.previousObject);
	   					if (cells[i].parentNode.previousSibling.nodeType != 1) {
	   						cells[i].parentNode.previousSibling.previousSibling.style.display=display;
	   					} else {
	   						cells[i].parentNode.previousSibling.style.display=display;
	   					}
	   					cells[i].parentNode.style.display=display;
	   					//cells[i].style.display="none";
	   				};
	   			};
	   		}
	   }//if   
	}//for
	
	var cells = document.getElementsByTagName('dd');
	cssDisplay(cells, press);
	var cells = document.getElementsByTagName('dt');
	cssDisplay(cells, press);
	
	var field=document.forms["user-feuserextension-pi2-fe_users_form"];
	var ckName ='';
	 for(i=0; i<field.elements.length; i++){
	 	
	 	if (field.elements[i].type=='checkbox') {
	 		ckName=field.elements[i].name;
	 		 if (ckName.indexOf('module_sys_dmail_category',0)!=-1) {
	 		 	field.elements[i].checked=false;
	 		 }
	 	 }
	  }
	
}