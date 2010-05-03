function cssDisplay(cells, press) {
/* ************************************************************************************************************
** Function cssDisplay
** 
** ********************************************************************************************************* */
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
};

function pressOrNotPress(me) {
/* ************************************************************************************************************
** Function pressOrNotPress
** 
** ********************************************************************************************************* */
	var press=false;
	var display='none';
	
	if (me.value==37) {press=true; display='block'};
	var cells = document.getElementsByTagName('input');
	for (var i = 0; i < cells.length; i++) { 
	   //doesn't work with ie->  className = cells[i].getAttribute("class"); 
	   className = cells[i].className
	     
	   if (className != null) {
	   		
	   		var tableau=className.split(' ');
	   		//alert(className+' '+tableau.length);
	   		if (tableau.length>1) {
	   			for (var j=1; j<tableau.length; j++) {
	   				if (tableau[j]=='press') {
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

function option2chk (me, meNum, essai) {
/* ************************************************************************************************************
** Function option2chk, permet de simuler un comportement de boutton d'option avec des cases à cocher
** 
** ********************************************************************************************************* */
	
	if (me.parentNode.className=='press' && me.checked) {
		
		for (var i = 0; i < essai.length; i++) { 
			//alert('i:'+essai[i]+' // number:'+meNum);
			if (meNum!=essai[i]) {
				document.getElementById('user-feuserextension-pi2-module_sys_dmail_category-'+essai[i]).checked=false;
			}
		}
	}
};

function group2chk(me) {
/* ************************************************************************************************************
** Function group2chk, Séléctione le group approprier et lance option2chk pour simuler un comportement
** de boutton d'option avec des cases à cocher
** ********************************************************************************************************* */
	// Chaque sous tableau contient la liste des cases à cocher qui seraient dans le même groupe si c'était des bouttons d'option.
	var groupList = new Array(
		new Array(42,43,44),
		new Array(46,47,48,49,50,51,52,53,54),
		new Array(56,57,58,59,60,61,62),
		new Array(64,65,66,67),
		new Array(69,70),
		new Array(72,73,74,75,76,77,78,79));
	
	// Récupère le numéro de la case à coché séléctionnée.
	var tempSplit=me.name.split('[');
	var number=tempSplit[3].split(']');
	// Détermine dans quel groupe se trouve la case à cocher, ensuite on fais appelle la fontion option2chk pour prendre le relais
	for (var i = 0; i < groupList.length; i++) {
		for (var j = 0; j < groupList[i].length; j++) {
			if (number[0]==groupList[i][j]) {
				option2chk (me, number[0], groupList[i]);
			}
		}
	}
};