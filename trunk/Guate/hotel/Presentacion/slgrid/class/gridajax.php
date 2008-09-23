<?php

class gridajax
{

function AjaxSort($grid_id, $column_order_key)
{
	$_SESSION[$grid_id]->order_column_index = $column_order_key; 

	if ($_SESSION[$grid_id]->columns[$column_order_key]->is_asc_order == true)
		$_SESSION[$grid_id]->columns[$column_order_key]->is_asc_order = false;
	else 
		$_SESSION[$grid_id]->columns[$column_order_key]->is_asc_order = true;

	return ($_SESSION[$grid_id]->CreateGrid());
}

function AjaxSaveColumnWidth($grid_id, $left_index, $left_width, $right_index, $right_width)
{
	$_SESSION[$grid_id]->columns[$left_index]->width = $left_width;
	$_SESSION[$grid_id]->columns[$right_index]->width = $right_width;
}

function AjaxChangePage($grid_id, $next)
{
	$_SESSION[$grid_id]->ChangePage($next);
	return ($_SESSION[$grid_id]->createGrid());
}

function AjaxGoToPage($grid_id, $go_to_page)
{
	$_SESSION[$grid_id]->GoToPage($go_to_page);
	return ($_SESSION[$grid_id]->CreateGrid());
}

function AjaxCreateExcelFile($grid_id)
{

	return ($_SESSION[$grid_id]->CreateExcelFile());
}

function AjaxDeleteRow($grid_id, $column_name, $column_value)
{
	$_SESSION[$grid_id]->DeleteRow($column_name, $column_value);
	
	return $_SESSION[$grid_id]->CreateGrid();
}

function AjaxShowEditForm($grid_id, $id_column_value)
{
	$_SESSION[$grid_id]->id_row_value_edit_form = $id_column_value;

	return $_SESSION[$grid_id]->CreateGrid();
}

function AjaxSaveForm($id_column, $id_value, $grid_id, $values_string)
{
	$values = array();
	$values = split("value_separator_grid", $_SESSION[$grid_id]->StripTags(trim($values_string)));

	$_SESSION[$grid_id]->SaveRow($id_column, $id_value, $values);
	$_SESSION[$grid_id]->id_row_value_edit_form = ""; //Remove inputs. 

	return( $_SESSION[$grid_id]->CreateGrid());
}

function AjaxSaveInsertForm($id_column, $grid_id, $values_string)
{
	$values = array();
	$values = split("value_separator_grid", $_SESSION[$grid_id]->StripTags(trim($values_string)));

	$_SESSION[$grid_id]->InsertRow($id_column, $values);

	return( $_SESSION[$grid_id]->CreateGrid());
}


function AjaxSetSelectedRow($grid_id, $row_id)
{
	$objResponse = new xajaxResponse();

	if ($row_id != $_SESSION[$grid_id]->selected_row_id)
	{
		$_SESSION[$grid_id]->selected_row_id = $row_id;
		$objResponse->addAssign($grid_id, "innerHTML", $_SESSION[$grid_id]->CreateGrid());
	}
		
	return $objResponse;
}

/*	von sf programmiert
	2007-08-21
*/
function AjaxRefreshSearch($titulo, $id_column, $grid_id, $values_string,$focus_name)
{
$values = split("value_separator_grid", $values_string);
if($titulo=="Reservas"){
	
	$search_value0 = $values[0];//nombre
	$search_value1 = $values[1];//apellido1
	$search_value2 = $values[2];//fecha_inicio
	$search_value3 = $values[3];//fecha_fin
	$search_value4 = $values[4];//imp_pagado
	$_SESSION[$grid_id]->search_focus=$focus_name;
	$_SESSION[$grid_id]->SetSearchStrings($search_value0,$search_value1,$search_value2,$search_value3,$search_value4);
	//$_SESSION[$grid_id]->SetSqlSelect('Id_cliente, nombre, apellido1, apellido2, pasaporte','cliente', 'apellido1 like "'.$search_value1.'%" and apellido2 like "'.$search_value2.'%" and nombre like "'.$search_value0.'%" and pasaporte like "'.$search_value3.'%"');
	$_SESSION["grid"]->SetSqlSelect('reserva.Id_res, nombre as Nombre, apellido1 as Apellido, fec_ini as "Fecha_Inicio", fec_fin as "Fecha_Fin", imp_pagado as Pagado','reserva LEFT JOIN cliente ON reserva.Id_cliente=cliente.Id_cliente', 'apellido1 like "'.$search_value1.'%" and fec_ini like "'.$search_value2.'%" and fec_fin like "'.$search_value3.'%" and imp_pagado like "'.$search_value4.'%" and nombre like "'.$search_value0.'%" and not exists (Select * from checkin where checkin.Id_res=reserva.Id_res)');
	$_SESSION["grid"]->SetUniqueDatabaseColumn("Id_res", false);         
}
else{	
	$search_value0 = $values[0];//firstname
	$search_value1 = $values[1];//apellido1
	$search_value2 = $values[2];//apellido2
	$search_value3 = $values[3];//pasaporte
	$_SESSION[$grid_id]->search_focus=$focus_name;
	$_SESSION[$grid_id]->SetSearchStrings($search_value0,$search_value1,$search_value2,$search_value3);
	$_SESSION[$grid_id]->SetSqlSelect('Id_cliente, nombre as Nombre, apellido1 as Apellido_1, apellido2 as Apellido_2, pasaporte as Pasaporte','cliente', 'apellido1 like "'.$search_value1.'%" and apellido2 like "'.$search_value2.'%" and nombre like "'.$search_value0.'%" and pasaporte like "'.$search_value3.'%"');
	$_SESSION[$grid_id]->SetUniqueDatabaseColumn("Id_cliente", false);   

}
	$_SESSION[$grid_id]->GoToPage(1);
	return ($_SESSION[$grid_id]->CreateGrid());
	
	//$_SESSION[$grid_id]->SetSqlSelect('p.id as id, p.firstname AS Vorname, p.surename AS Nachname, c.name AS Unternehmen, con.name AS Land', 'person p LEFT JOIN company c ON c.ID=p.company_id LEFT JOIN countries con ON p.country_id=con.ID', 'p.surename!="" and p.surename like "'.$search_value1.'%" and p.firstname like "'.$search_value0.'%" and c.name like "'.$search_value2.'%" and con.name like "'.$search_value3.'%"');
}


}
?>
