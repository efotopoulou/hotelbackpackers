<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Admin Users</title>

<link rel="stylesheet" type="text/css" href="/common/jqgrid_demo/themes/sand/grid.css" title="sand" media="screen" />
<link rel="stylesheet" type="text/css" media="screen" href="/common/jqgrid_demo/themes/jqModal.css" />
<script src="/common/jqgrid_demo/js/jquery.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jquery.ui.all.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jquery.layout.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jquery.jqTree.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jquery.jqDynTabs.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/ui.datepicker.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jquery.jqGrid.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jqModal.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jqDnR.js" type="text/javascript"></script>
<script src="/common/jqgrid_demo/js/jquery.tablednd.js" type="text/javascript"></script>
<script type="text/javascript" src="/common/jqgrid_demo/js/styleswitch.js"></script>


</head>
<body>
        <div  style="margin:60px">
<br />
	<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table> 
	<div id="pager2" class="scroll" style="text-align:center;"></div>
	<input type="BUTTON" id="bedata" value="Edit Selected" />
<script type="text/javascript">
// We use a document ready jquery function.
jQuery("#list2").jqGrid({
    url:'jsonprueba.php?nd='+new Date().getTime(),
    datatype: "json",
    colNames:['Nombre','Perfil','Password'],
    // colModel array describes the model of the column.
    // name is the name of the column,
    // index is the name passed to the server to sort data
    // note that we can pass here nubers too.
    // width is the width of the column
    // align is the align of the column (default is left)
    // sortable defines if this column can be sorted (default true)
    colModel:[
        {name:'Nombre',index:'Nombre', width:120, editable:true,editoptions:{size:10},sortable:false},
        {name:'Perfil',index:'Perfil', width:120, editable:true,editoptions:{size:10},sortable:false},
        {name:'Password',index:'Password', width:120,editable:true,editoptions:{size:10},sortable:false}
    ],
    // pager parameter define that we want to use a pager bar
    // in this case this must be a valid html element.
    // note that the pager can have a position where you want
    pager: jQuery('#pager2'),
    // rowNum parameter describes how many records we want to
    // view in the grid. We use this in example.php to return
    // the needed data.
    rowNum:10,
    // rowList parameter construct a select box element in the pager
    //in wich we can change the number of the visible rows
    rowList:[10,20,30],
    // path to mage location needed for the grid
    imgpath: '/common/img/jqgrid',
    // sortname sets the initial sorting column. Can be a name or number.
    // this parameter is added to the url
    sortname: 'id',
    height: "100%", 
    //viewrecords defines the view the total records from the query in the pager
    //bar. The related tag is: records in xml or json definitions.
    viewrecords: true,
    //sets the sorting order. Default is asc. This parameter is added to the url
    sortorder: "desc",
    editurl:"someurl.php"
});
$("#bedata").click(function(){
	var gr = jQuery("#list2").getGridParam('selrow');
	if( gr != null ) jQuery("#list2").editGridRow(gr,{
		reloadAfterSubmit:false
		});
	else alert("Please Select Row");
});

</script>
<br /> 
	</div>
</div>

</body>
</html>