function hotkeys(){
	jQuery.hotkeys.add('n0',function (){ calmousedown("0","0");changeClass("0")});
	jQuery.hotkeys.add('n1',function (){ calmousedown("1","1");changeClass("1")});
	jQuery.hotkeys.add('n2',function (){ calmousedown("2","2");changeClass("2")});
	jQuery.hotkeys.add('n3',function (){ calmousedown("3","3");changeClass("3")});
	jQuery.hotkeys.add('n4',function (){ calmousedown("4","4");changeClass("4")});
	jQuery.hotkeys.add('n5',function (){ calmousedown("5","5");changeClass("5")});
	jQuery.hotkeys.add('n6',function (){ calmousedown("6","6");changeClass("6")});
	jQuery.hotkeys.add('n7',function (){ calmousedown("7","7");changeClass("7")});
	jQuery.hotkeys.add('n8',function (){ calmousedown("8","8");changeClass("8")});
	jQuery.hotkeys.add('n9',function (){ calmousedown("9","9");changeClass("9")});

	jQuery.hotkeys.add('e',function (){ efectivo();changeClass("Efectivo")});
	
/*	jQuery.hotkeys.add('right',mesaRight);
	jQuery.hotkeys.add('left',mesaLeft);
	jQuery.hotkeys.add('up',mesaUpDown);
	jQuery.hotkeys.add('down',mesaUpDown);*/
	
}

function desHotkeys(){
	jQuery.hotkeys.remove('n0');
	jQuery.hotkeys.remove('n1');
	jQuery.hotkeys.remove('n2');
	jQuery.hotkeys.remove('n3');
	jQuery.hotkeys.remove('n4');
	jQuery.hotkeys.remove('n5');
	jQuery.hotkeys.remove('n6');
	jQuery.hotkeys.remove('n7');
	jQuery.hotkeys.remove('n8');
	jQuery.hotkeys.remove('n9');

	jQuery.hotkeys.remove('backspace');
	jQuery.hotkeys.remove('e');
}
function mesaRight(){
  var mesa = 0;
  if (main.currentMesa) mesa=main.currentMesa;
  if (mesa < (main.numMesas/2)) mesa=(mesa+1)%(main.numMesas/2);
  else mesa=((mesa+1-main.numMesas/2)%(main.numMesas/2))+(main.numMesas/2);
  mesamousedown("Mesa"+mesa);
}
function mesaLeft(){
  var mesa = 0;
  if (main.currentMesa) mesa=main.currentMesa;
  if (mesa < (main.numMesas/2)) mesa=(mesa-1+(main.numMesas/2))%(main.numMesas/2);
  else mesa=((mesa-1)%(main.numMesas/2))+(main.numMesas/2);
  mesamousedown("Mesa"+mesa);
}
function mesaUpDown(){
  var mesa = 0;
  if (main.currentMesa) mesa=parseInt(main.currentMesa);
  if (mesa < (main.numMesas/2)) mesa=mesa+(main.numMesas/2);
  else mesa=mesa-(main.numMesas/2);
  mesamousedown("Mesa"+mesa);
}
