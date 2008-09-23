<?php 
// *****************************************************************************
// *  slGrid 2.0                                                            *
// *  http://slgrid.senzalimiti.sk                                             *
// *                                                                           *
// *  Copyright (c) 2006 Senza Limiti s.r.o.                                   *
// *                                                                           *
// *  This program is free software; you can redistribute it and/or            *
// *  modify it under the terms of the GNU General Public License              *
// *  as published by the Free Software Foundation; either version 2           *
// *  of the License, or (at your option) any later version.                   *
// *                                                                           *
// *  This program is distributed in the hope that it will be useful,          *
// *  but WITHOUT ANY WARRANTY; without even the implied warranty of           *
// *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            *
// *  GNU General Public License for more details.                             *
// *                                                                           *
// *  For commercial licenses please contact Senza Limiti at                   *
// *  - http://www.senzalimiti.sk                                              *
// *  - info(at)senzalimiti.sk                                                 *
// *****************************************************************************

set_time_limit(0);
ob_start("ob_gzhandler");

class Grid
{
	var $GRID_SOURCE= "";   //von sf    
	var $id;  
	var $database;
	var $databasetype;
	var $sql_table;

	var $width = 600;
	var $order_column_index = 0;
	var $columns = array();
	var $number_of_rows_each_page = 7;
	var $number_of_rows = 0; //von sf
	var $current_page = 1;
	var $total_pages;
	var $print = false;
	var $sql_where = "";
	var $sql_columns = "*";
	var $mode; /*= MODE_VIEW, MODE_EDIT*/
	var $id_row_value_edit_form = ""; 
	var $show_excel_ico = false;
	var $show_print_ico = false;
	var $strip_html_tags = false;
	var $user_defined_columns = array();
	var $table_columns = 0;
	var $column_id;
	var $text_page = "P&aacute;gina";
	var $text_gotopage = "Ir a P&aacute;gina";
	var $text_totalrows = "Registros totales:";
	var $text_edit = "Edit";
	var $text_save = "Save";
	var $text_delete = "Delete";
	var $text_add = "Add";
	var $text_cancel = "Cancel";
	var $text_search = "Buscar"; //von sf
	var $text_choose = "Select"; //von sf
	var $editmode_add = false;
	var $editmode_edit = false;
	var $editmode_delete = false;
	var $editmode_search = false;
	var $editmode_choose = true;
	var $edit_column_width = 58;
	var $delete_column_width = 45;
	var $sql_table_override = "";
	var $database_plugins = array();
	var $all_plugins = array();
	var $search_strings = array('','','','',''); //von sf
	var $search_focus = ""; //von sf
	
	function Grid($id)
	{
		$this->id = $id;
	}

	function StripTags($str)
	{
		if ($this->strip_html_tags == true)
			return strip_tags($str);
		else
			return $str;
	}
	
	function PrintGrid($mode)
	{
		$this->mode = $mode;
		
		if ($this->mode == MODE_EDIT and $this->editmode_add == false and $this->editmode_edit == false and $this->editmode_delete == false and $this->editmode_choose == false)
			$this->mode = MODE_VIEW;
		
		echo $this->MainDivTop();
		echo $this->CreateGrid();
		echo $this->MainDivBottom();
	}

	function MainDivTop()
	{
		echo "<div class='".$this->id."_maindiv' id=\"".$this->id."\" width=$this->width>";
	}
	
	function MainDivBottom()
	{
		echo "</div>";
	}
	
	function LoadColumnNames()
	{
		$column_names = array();
		$column_types = array();
		
		$column_names = $this->database->GetColumnNames($this->sql_columns, $this->sql_table);
		
		if (count($this->columns) == 0)
		{
			$keys = array_keys($this->columns);
			$index = 0;
			foreach ($column_names as $name)
			{
				$col = new Column($name);
				
				foreach ($this->user_defined_columns as $user_column)
				{
					if ($user_column->name == $name)
					{
						$col = $user_column;
						break;
					}	
				}
				
				/*if ($col->is_id == false and $col->show == true)*/
				if ($col->show == true)
					$this->columns[] = $col;	 
				
				$index++;
			}
				
			if ($this->mode == MODE_EDIT)
			{
				/*Edit*/
				$col = new Column("");
				$col->width = $this->edit_column_width;
				if($this->editmode_edit == false OR $this->editmode_choose == false)
				{
					$col->show=false;
				}
				$this->columns[] = $col;	 

				/*Delete*/
				$col = new Column("");
				$col->width = $this->delete_column_width;

				if($this->editmode_delete == false)
				{
					$col->show=false;
				}
				$this->columns[] = $col;	 
			}

			$this->table_columns = ((count($this->columns)*2)-1);

			$defined_width = 0;
			$defined_columns = 0;
			foreach ($this->columns as $column)
			{	
				if ($column->width != 0)
				{
					$defined_width += $column->width;
					$defined_columns++;
				}
			}
			
			$index = 0;
			foreach ($this->columns as $column)
			{
				if ($this->columns[$index]->width == 0)
				{
					$this->columns[$index]->width = round(($this->width-$defined_width)/(count($this->columns)-$defined_columns))  - 10;
				}
				$index++;
			}
		}
	}
	
	function CreateGrid()
	{
	
		if (isset($_GET["grid_id"]) == true && $this->show_print_ico == true)
			$this->print = true;
			
		if(isset($_GET[$this->id."_change_page"]))
		{
			$this->ChangePage($_GET[$this->id."_change_page"]);
		}		

		if(isset($_GET[$this->id."_order"]))
		{
			$this->order_column_index = $_GET[$this->id."_order"]; 
			
			if ($this->columns[$_GET[$this->id."_order"]]->is_asc_order == true)
				$this->columns[$_GET[$this->id."_order"]]->is_asc_order = false;
			else 
				$this->columns[$_GET[$this->id."_order"]]->is_asc_order = true;
		}				

		if(isset($_GET[$this->id."_go_to_page_number"]))
		{
			$this->GoToPage($_GET[$this->id."_go_to_page_number"]);
		}	

		$this->excel_info = "";
		$grid = "";
		
		if ($this->database->Connect() == false)
		{
			return "Could not connect to database...";
		}
		
		$this->LoadColumnNames();
		
		$rows = $this->database->GetRows($this->sql_columns, 
											$this->sql_table, 
											$this->sql_where,
											$this->columns[$this->order_column_index]->name, 
											$this->columns[$this->order_column_index]->is_asc_order,
											$this->print == true ? "":$this->current_page * $this->number_of_rows_each_page);

		if($this->search_focus==""){$this->search_focus="grid_insert_1";}
		$table_main = "	<script>
							function focusForm(){
								if(document.getElementById(\"".$this->search_focus."\")){
								document.getElementById(\"".$this->search_focus."\").focus();
							}
								document.getElementById(\"".$this->search_focus."\").focus();
								document.getElementById(\"".$this->search_focus."\").value = document.getElementById(\"".$this->search_focus."\").value;
							}
			      		</script>";
		
		$table_main .= "<table cellspacing='0' cellpadding='0' border='0' class='".$this->id."_table'>";
		$number_of_rows = 0;
		
		if ($rows != false)
		{
			$number_of_rows = $this->database->GetNumberOfRows($this->sql_table, $this->sql_where);
			$this->number_of_rows = $number_of_rows;
			
			$table_main .= "<tr class='".$this->id."_table_header'>";
			$table_main .= "<td colspan='".$this->table_columns."'>";
			
			$table_name = "";
			if ($this->sql_table_override != "")
				$table_name = $this->sql_table_override;
			else
				$table_name = $this->sql_table;
				
			$table_main .= 				"&nbsp;".$table_name;
			
			$table_main .= "</td>";
			$table_main .= "</tr>";
			
			$table_main .= "<tr>";
			
			$order_column_keys = array_keys($this->columns);
			$order_column_index = 0;
			$first_shown_order_column = true;
			
			foreach ($this->columns as $objColumn)
			{
				if ($this->print == true and $this->mode == MODE_EDIT)
				{
					if ($order_column_index == (count($this->columns)-2))
						break;
				}

				if ($objColumn->show == true)
				{
					/*if ($order_column_index != 0)*/
					if ($first_shown_order_column == false)
					{
						if ($this->print == true)
							$javascript_order_column_resize = "";
						else
						{	
							$tmp_number_of_rows_each_page = $this->number_of_rows_each_page;
							if ($this->mode == MODE_EDIT)
								$tmp_number_of_rows_each_page++;
							
							$javascript_order_column_resize = "onmousedown=\"SetCellNames('".$this->id."', '".$this->id."_column_".($order_column_index-1)."','".$this->id."_column_".$order_column_index."',".$tmp_number_of_rows_each_page.", ".($order_column_index-1).", '".$this->id."_column_'); SetMouseDown(true);\"";
						}
						$table_main .= "<td ".$javascript_order_column_resize." class=\"".$this->id."_column_resize\">";
						$table_main .= "</td>";
					}
					$first_shown_order_column = false;
					
					if ($this->print == true or 
						($this->mode == MODE_EDIT and ($order_column_index == (count($this->columns)-1) or $order_column_index == count($this->columns)-2)))
					{
						$javascript_order_column_sort = "";
					}
					else
						$javascript_order_column_sort = "onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxSort', '$this->id', '".$order_column_keys[$order_column_index]."');\"";
	
					$table_main .= "<td  ".$javascript_order_column_sort."  class=\"".$this->id."_table_header_row\" id=\"".$this->id."_column_".$order_column_index."\" style='width: ".$objColumn->width."px' onMouseOver='this.className =\"".$this->id."_table_header_row_hover\"' onMouseOut='this.className=\"".$this->id."_table_header_row\"'>";
					
					$column_name = "";
					if ($objColumn->user_defined_name != "")
						$column_name = $objColumn->user_defined_name;
					else
						$column_name = $objColumn->name;
					
					$table_main .= "<a href='".basename($_SERVER["SCRIPT_NAME"])."?".$this->id."_order=".$order_column_keys[$order_column_index]."' onClick='return(false);'>$column_name</a>";
	
					if ($this->order_column_index == $order_column_index)
					{	
						if ($objColumn->is_asc_order == true)
							$table_main .= "<img src=\"".$this->GRID_SOURCE."img/slgrid/up.gif\" alt=\"\" />";
						else
							$table_main .= "<img src=\"".$this->GRID_SOURCE."img/slgrid/down.gif\" alt=\"\" />";
					}
					
					$table_main .= "</td>";
				}
				$order_column_index++;
			}
			$table_main .= "</tr>";
			
			$row_index = 0;
			$visible_row_index = 0;
			
			reset($this->columns);

			for($i=0; $i<count($this->columns);$i++)
			{
				while(list($plugin_name, $args) = each($this->all_plugins))
					{
						$this->columns[$i]->plugins[$plugin_name] = $args;
					}
					reset($this->all_plugins);
				}
			reset($this->columns);

			if ($this->mode == MODE_EDIT)
			{
				$insert_row = array();
				$row_index = -1;
				
				foreach ($this->columns as $column)
					$insert_row[$column->name] = "";
				
				$this->CreateRow($insert_row, $row_index, $visible_row_index, $table_main);
				$row_index++;
			}
			
			$check_id_column = true;
			
			while ($row = $this->database->FetchArray($rows))
			{
				if ($check_id_column == true)
				{
					if (array_key_exists($this->column_id->name, $row) == false)
						return "Can´t find the id column \"".$this->column_id->name."\".<br/>The name must be exactly the same as the id database column.<br/>It´s case-sensitive (= small/large characters matter).";
				}
				$check_id_column = false;
				
				$this->CreateRow($row, $row_index, $visible_row_index, $table_main);
				$row_index++;
			}
		}
		
		if ($this->print == false)
		{
			$table_main .= "<tr class='".$this->id."_footer'>";
			$table_main .= "<td colspan=\"".$this->table_columns."\" >";

			$divided_total = $number_of_rows/$this->number_of_rows_each_page;
			$this->total_pages =  intval("".($divided_total));
			
			if ($number_of_rows % $this->number_of_rows_each_page != 0)
				$this->total_pages++;
				
			if ($this->total_pages == 0)
				$this->total_pages = 1;
			
		/*	$table_main .= 		"<span style=\"width: 200px;text-align:right;float:right;\">";
			$table_main .= 			"<input type='submit' name='person_new' value='neue Person anlegen' class='input'>";
			$table_main .= 		"</span>";
		*/	
			$table_main .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"  class='tbl_".$this->id."_footer'>";
			$table_main .= 	"<tr>";
			$table_main .= 		"<td>";
			$table_main .= 			"&nbsp;".$this->text_page."&nbsp;";
			$table_main .= 		"</td>";
			$table_main .= 		"<td>";
			$table_main .= 			"<div id=\"".$this->id."_current_page_number\">".$this->current_page."</div>";
			$table_main .= 		"</td>";
			$table_main .= 		"<td>";
			$table_main .= 			"/".$this->total_pages;
			$table_main .= 		"</td>";
	
			$table_main .= 		"<td style=\"width: 10px;\">";
			$table_main .= 		"</td>";
	
		
			$table_main .= 		"<td>";
			$table_main .= 				"<a href='".$this->GRID_SOURCE."".basename($_SERVER["SCRIPT_NAME"])."?".$this->id."_change_page=previous' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxChangePage', '$this->id', 'previous'); return(false);\" style=\"cursor: pointer;\">";
			$table_main .= 					"<img src=\"".$this->GRID_SOURCE."img/slgrid/arrow_left.png\" alt=\"\" border=0/>";
			$table_main .= 				"</a>";
			$table_main .= 		"</td>";
			
	
			$table_main .= 		"<td style=\"width: 5px;\">";
			$table_main .= 		"</td>";
	
			$table_main .= 		"<td>";
			$table_main .= 				"<a href='".$this->GRID_SOURCE."".basename($_SERVER["SCRIPT_NAME"])."?".$this->id."_change_page=next' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxChangePage', '$this->id', 'next'); return(false);\" style=\"cursor: pointer;\">";
			$table_main .= 					"<img src=\"".$this->GRID_SOURCE."img/slgrid/arrow_right.png\" alt=\"\" border=0/>";
			$table_main .= 				"</a>";
			$table_main .= 		"</td>";
			
			$table_main .= 		"<td style=\"width: 10px;\">";
			$table_main .= 		"</td>";
	
			$table_main .= 		"<td>";
			$table_main .= 			"<input class=\"grid_input\" type=\"text\" name=\"".$this->id."_go_to_page_number\" id=\"".$this->id."_go_to_page_number\" value=\"".$this->current_page."\" style=\"width: 50px;\" />&nbsp;";

			$table_main .= 			"<input class=\"grid_button\" onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxGoToPage', '$this->id', document.getElementById('".$this->id."_go_to_page_number').value);return(false);\" type=\"submit\" value=\"".$this->text_gotopage."\" style=\"background-color: #f6f6f6;\" />";
			$table_main .= 		"</td>";
			
			$table_main .= 		"<td style=\"width: 10px;\">";
			$table_main .= 		"</td>";
	
			$table_main .= 		"<td>";
			$table_main .= 			$this->text_totalrows." ".$number_of_rows;
			$table_main .= 		"</td>";
			
			if ($this->show_excel_ico == true)
			{
				$table_main .= 		"<td style=\"width: 150px;\">";
				$table_main .= 		"</td>";
				$table_main .= 		"<td>";				
				$table_main .= 					"<a href=\"Presentacion/slgrid/excel.php?grid_id=".$this->id."\"><img border=\"0\" src=\"img/slgrid/excel.png\" alt=\"\" /></a>";
				$table_main .= 		"</td>";
			}			
			if ($this->show_print_ico == true)
			{
				$table_main .= 		"<td style=\"width: 20px;\">";
				$table_main .= 		"</td>";
				$table_main .= 		"<td>";
				$table_main .= 					"<a target=\"_blank\" href=\"Presentacion/slgrid/class/print.php?grid_id=".$this->id."\"><img border=\"0\" src=\"img/slgrid/print2.gif\" alt=\"\" /></a>";
				$table_main .= 		"</td>";
			}

			$table_main .= 	"</tr>";
	
			$table_main .= "</table>";
			$table_main .= "</td>";
			$table_main .= "</tr>";
		}
		$table_main .= "</table>";
		$table_main .= "<script>
							focusForm();
			      		</script>";
		
		$grid .= $table_main;
		
		$this->database->FreeResult($rows);
		$this->database->Disconnect();
	
		return $grid;
	}
	
	function CreateRow($row, $row_index, &$visible_row_index, &$table_main)
	{
		$xajax_select_row = "";
		$column_index = 0;
		$first_column = true;
		
		foreach ($this->columns as $objColumn)
		{
			$input_extra_height = false;
			
			
			if ($row_index == -1)
				$input_extra_height = true;

			if ($this->print == true and $this->mode == MODE_EDIT)
			{
				if ($column_index == (count($this->columns)-2))
					break;
			}
		
			if ($objColumn->show == true)
			{
				if ($this->ShowCurrentRowIndex($row_index) == true)
				{
					if ($first_column == false)
					{
						$table_main .= "<td class='".$this->id."_table_resize_row'>";
						$table_main .= "";
						$table_main .= "</td>";
					}
					else
					{						//$this->editmode_choose == true

						if($visible_row_index==0){
							$table_main .= "<tr class='grid_table_row_search'>";
						}
						else
						{	//solo se ejecuta el onclick si se cambia la row elegida(para que vaya el ondblclick)
							//if($this->id_row_value_edit_form)
							//	$selected_row=$this->id_row_value_edit_form;	
							//else
							//	$selected_row=-1;	//la 1ª vez permitimos que se ejecute el onclick
							//onclick=\"if(".$selected_row."!=".$row[$this->column_id->name]."){changevalue('person_id','".$row[$this->column_id->name]."');HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxShowEditForm', '$this->id', '".$row[$this->column_id->name]."');return false}\"
							if($this->mode == MODE_EDIT)
								$onclick="onclick=\"changevalue('person_id','".$row[$this->column_id->name]."')\" ";
							else
								$onclick="";
							$table_main .= sprintf("<tr class='%s' $onclick >", $row_index %2 == 0 ? $this->id."_table_row_even": $this->id."_table_row_uneven");
						}
					

					}
					$first_column = false;
				}
				
				if ($this->mode == MODE_EDIT and $row_index == -1)
				{
					/*Insert form.*/
					$xajax_select_row = "";
					$cell_info = htmlentities($this->StripTags($row[$objColumn->name]));
					if ($this->editmode_search == true)
					{
						$minus = 1;
						if($this->editmode_search == true)
							$minus = 0;
						
						if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-1+$minus))
							$cell_info = "";
						else if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-2+$minus))
							$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxSaveInsertForm', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."));\">".$this->text_add."</a>";
						else if($objColumn->editable != false)
						{
							//( PLugin code
					
							$plugin_found = false;
							while(list($plugin_name, $args) = each($objColumn->plugins))
							{
								$file_name = GRID_SOURCE."plugins/class.".$plugin_name.".php";
								if(file_exists($file_name))
								{
									
									require_once($file_name);
									$plugin = $plugin_name."_Plugin";
									$plugin = new $plugin;
									
									$args["name"] = $this->id."_insert_".$column_index;
									$args["mode"] = "insert";
									$cell_info = $plugin->generateContent($row[$objColumn->name], $args);
									$plugin_found = true;
								}
							}
							reset($objColumn->plugins);		
							
							if(!$plugin_found)
								$cell_info = "<input onclick=\"SetIsInputClick(true);\" class=\"grid_input\" type=\"text\" name=\"".$this->id."_insert_".$column_index."\" id=\"".$this->id."_insert_".$column_index."\" value='".$cell_info."'/>";
						}
						else
						{
							$cell_info = $cell_info;
						}
					}
					else if ($this->editmode_choose == true) //Suchen
					{
						$minus = 1;
						if($this->editmode_choose == true)
							$minus = 0;
						
						if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-1+$minus))
							$cell_info = "";
						else if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-2+$minus)){
							//$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxRefreshSearch', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."));\">".$this->text_search."</a>";

							if($this->number_of_rows==0){
								$cell_info="<input type='submit' name='person_new' value='a&ntilde;adir' class='search_add'>";
							}
						}
						else if($objColumn->editable != false)
						{
							//( PLugin code
							$plugin_found = false;
							while(list($plugin_name, $args) = each($objColumn->plugins))
							{
								$file_name = GRID_SOURCE."plugins/class.".$plugin_name.".php";
								if(file_exists($file_name))
								{
									require_once($file_name);
									$plugin = $plugin_name."_Plugin";
									$plugin = new $plugin;
									
									$args["name"] = $this->id."_insert_".$column_index;
									$args["mode"] = "insert";
									$cell_info = $plugin->generateContent($row[$objColumn->name], $args);
									$plugin_found = true;
								}
							}
							reset($objColumn->plugins);		
							
							if(!$plugin_found){
								//suchfelder
								$cell_focus="";
								if($this->id."_insert_".$column_index==$this->search_focus)
								{$cell_focus=$this->id."_insert_".$column_index."==".$this->search_focus;
								
								}//input de busqueda
								$cell_info = "<input onclick=\"SetIsInputClick(true);\" class=\"grid_input\" type=\"text\" name=\"".$this->id."_insert_".$column_index."\" id=\"".$this->id."_insert_".$column_index."\" value='".$this->search_strings[$column_index]."' onkeyup=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxRefreshSearch', '".$this->GetTitleName()."', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."), '".$this->id."_insert_".$column_index."');\"/>";
								//$cell_info = "<input onclick=\"SetIsInputClick(true);\" class=\"grid_input\" type=\"text\" name=\"".$this->id."_insert_".$column_index."\" id=\"".$this->id."_insert_".$column_index."\" value='".$this->search_strings[$column_index]."' onkeyup=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxRefreshSearch', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."), '".$this->id."_insert_".$column_index.");\"/>";//.$this->id."_insert_".$column_index.".focus();\
								//$cell_info = "<input onclick=\"SetIsInputClick(true);\" class=\"grid_input\" type=\"text\" name=\"".$this->id."_insert_".$column_index."\" id=\"".$this->id."_insert_".$column_index."\" value='".$this->search_strings[$column_index]."' onkeyup=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxRefreshSearch', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."));\"/>";
								//$cell_info = "<input onclick=\"SetIsInputClick(true);\" class=\"grid_input\" type=\"text\" name=\"".$this->id."_insert_".$column_index."\" id=\"".$this->id."_insert_".$column_index."\" value='".$cell_info."' onkeyup=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxRefreshSearch', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."));".$this->id."_insert_".$column_index.".focus();\"/>";
								//echo "a".$this->GetTitleName();
							}	
						}
						else
						{
							$cell_info = $cell_info;
						}
					}
					else if ($this->editmode_add == true)
					{
						$minus = 1;
						if($this->editmode_edit == true)
							$minus = 0;
						
						if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-1+$minus))
							$cell_info = "";
						else if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-2+$minus))
							$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxSaveInsertForm', '".$this->column_id->name."', '".$this->id."', GetFormValues('".$this->id."_insert_', ".(count($this->columns)-2)."));\">".$this->text_add."</a>";
						else if($objColumn->editable != false)
						{
							//( PLugin code
					
							$plugin_found = false;
							while(list($plugin_name, $args) = each($objColumn->plugins))
							{
								$file_name = GRID_SOURCE."plugins/class.".$plugin_name.".php";
								if(file_exists($file_name))
								{
									
									require_once($file_name);
									$plugin = $plugin_name."_Plugin";
									$plugin = new $plugin;
									
									$args["name"] = $this->id."_insert_".$column_index;
									$args["mode"] = "insert";
									$cell_info = $plugin->generateContent($row[$objColumn->name], $args);
									$plugin_found = true;
								}
							}
							reset($objColumn->plugins);		
							
							if(!$plugin_found)
								$cell_info = "<input onclick=\"SetIsInputClick(true);\" class=\"grid_input\" type=\"text\" name=\"".$this->id."_insert_".$column_index."\" id=\"".$this->id."_insert_".$column_index."\" value='".$cell_info."'/>";
						}
						else
						{
							$cell_info = $cell_info;
						}
					}
					else
						$cell_info = "";
				}
				else if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-1))
				{	
				
					if ($this->editmode_delete == true)
					{
						if ($this->id_row_value_edit_form != "" and $row[$this->column_id->name] == $this->id_row_value_edit_form) 
							$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxShowEditForm', '$this->id', 'false');\">".$this->text_cancel."</a>";
						else
							$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxDeleteRow', '$this->id', '".$this->column_id->name."','".$row[$this->column_id->name]."');\">".$this->text_delete."</a>";
					}
					else
						$cell_info = "";
				}
				else if ($this->mode == MODE_EDIT and $column_index == (count($this->columns)-2))
				{
					if ($this->editmode_choose == true)
					{
						//$cell_info = "<a href=\"javascript:changevalue('person_id','".$row[$this->column_id->name]."');/*document.getElementById('info').focus();*/\" onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxShowEditForm', '$this->id', '".$row[$this->column_id->name]."');/*document.getElementById('info').focus();*/\">".$this->text_choose."</a>";
						$cell_info="";
					}
					else if ($this->editmode_edit == true)
					{
						if ($this->id_row_value_edit_form != "" and $row[$this->column_id->name] == $this->id_row_value_edit_form) 
							$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxSaveForm', '".$this->column_id->name."', '".$row[$this->column_id->name]."', '".$this->id."', GetFormValues('".$this->id."_edit_', ".(count($this->columns)-2)."));\">".$this->text_save."</a>";
						else
							$cell_info = "<a href='#' onclick=\"HTML_AJAX.replace('$this->id', 'gridajax', 'AjaxShowEditForm', '$this->id', '".$row[$this->column_id->name]."');\">".$this->text_edit."</a>";
					}
						else 
						$cell_info = "";
				}
				else 
				{
					$cell_info = htmlentities($this->StripTags($row[$objColumn->name]));
					//( PLugin code
					$plugin_found = false;
					while(list($plugin_name, $args) = each($objColumn->plugins))
					{
						$file_name = GRID_SOURCE."plugins/class.".$plugin_name.".php";
						if(file_exists($file_name))
						{
							
							require_once($file_name);
							$plugin = $plugin_name."_Plugin";
							$plugin = new $plugin;
							$args["id"] = $row[$this->column_id->name];
							
							if($this->id_row_value_edit_form != "" and $row[$this->column_id->name] == $this->id_row_value_edit_form and ($objColumn->editable != false))
							{
								$args["name"] = $this->id."_edit_".$column_index;
								$args["mode"] = "edit";
							}
							else
							{
								$args["field"] = $objColumn->name;
								$args["mode"] = "view";
							}
							$cell_info = $plugin->generateContent($cell_info, $args);
							$plugin_found = true;
						}
					}
					reset($objColumn->plugins);		
					
					/*Edit form.*/
					if ($this->id_row_value_edit_form != "" and $row[$this->column_id->name] == $this->id_row_value_edit_form and ($objColumn->editable != false)) 
					{
						if(!$plugin_found)
						{
							$xajax_select_row = "bgcolor='#F58220'";
							//$cell_info = "<input class=\"grid_input\" type=\"text\" name=\"".$this->id."_edit_".$column_index."\" id=\"".$this->id."_edit_".$column_index."\" value='".str_replace("'", "", $cell_info)."' onFocus='this.select();'/>";
							//$cell_info = "s<input class=\"grid_input\" type=\"text\" name=\"".$this->id."_edit_".$column_index."\" id=\"".$this->id."_edit_".$column_index."\" value='".str_replace("'", "", $cell_info)."' onFocus='this.select();'/>";
						}
						$input_extra_height = true;
					}
				}	

				if ($this->ShowCurrentRowIndex($row_index) == true)
				{
					$extra_height = 0;
					$input_javascript = "";
					if ($input_extra_height == true)
					{
						$input_javascript = "onclick=\"SetIsInputClick(true);\"";
						$extra_height = 4;
					}	
					
					$table_main .= "<td ".$xajax_select_row.">";
					$table_main .= "<div ".$input_javascript." id=\"".$this->id.
								"_cell_".$column_index."_row_".$visible_row_index."\" 
								class=\"".$this->id."_table_cell\">";
					$table_main .= $cell_info;
					$table_main .= "</div>";
					$table_main .= "</td>";
				}
			}
			$column_index++;
		}

		if ($this->ShowCurrentRowIndex($row_index) == true)
		{
			$table_main .= "</tr>";
			$visible_row_index++;
		}
	}
	
	function ShowCurrentRowIndex($row_index)
	{
		if ($row_index == -1 and $this->print == false)
			return true;
		else if ($this->print == true and $row_index == -1)
			return false;
		else if ($this->print == true)
			return true;
		else if ($row_index >= (($this->current_page * $this->number_of_rows_each_page)-$this->number_of_rows_each_page) and 
					$row_index < ($this->current_page * $this->number_of_rows_each_page))
		{
			return true;
		}
		else
			return false;
	}
	
	function SetDatabaseConnection($database, $username, $password, $hostname = "localhost", $databasetype = DATABASE_MYSQL) /*Database, Username, Password, DatabaseType.*/		
	{
		$this->databasetype = $databasetype;
		
		if ($databasetype == DATABASE_MYSQL)
			$this->database = new MySql($database, $username, $password, $hostname);
		else if ($databasetype == DATABASE_POSGRESQL)
			$this->database = new PosgreSql($database, $username, $password, $hostname);
	}
	
	function SetSqlSelect($columns, $table, $where = "")		
	{
		$this->sql_columns = $columns;	
		$this->sql_table = $table;
		$this->sql_where = $where;
	}
	
	function CreateExcelFile()
	{
		$objExcel = new Excel();
		$objExcel->SetData($this->excel_info);
		
		$objExcel->GenerateExcelFile(GRID_SOURCE."excel/".$this->id.".xls");
	}
	
	function ChangePage($next)
	{
		if ($next == "next")
		{
			if (($this->current_page + 1) <= $this->total_pages)
				$this->current_page++;
		}
		else
		{
			if (($this->current_page - 1) > 0)
				$this->current_page--;
		}
	}
	
	function GoToPage($go_to_page)
	{
		if (is_numeric($go_to_page) == true)
		{
			$go_to_page = intval($go_to_page);
			if ($go_to_page >= 1 and $go_to_page <= $this->total_pages)
				$this->current_page = $go_to_page;
		}
	}
	
	function SaveRow($id_column, $id_value, $column_values)
	{
		$this->database->Connect();	
		
		$column_names = array();
		$column_values_new = array();
		$index = 0;
		foreach ($this->columns as $column)
		{
			if ($index < (count($this->columns)-2))
			{
				if ($id_column != $column->name && ($column->editable != false))
				{
					$column_names[] = $column->name;	
					$column_values_new[] = $this->StripTags($column_values[$index]); 
				}
			}
			$index++;
		}
			
		$this->database->Update($this->sql_table, $id_column, $id_value, $column_names, $column_values_new);
		
		$this->database->Disconnect();	
	}
	
	function InsertRow($id_column, $column_values)
	{
		$success = false;
		
		$this->database->Connect();	
		
		$column_names = array();
		$column_values_new = array();
		$index = 0;
		foreach ($this->columns as $column)
		{
			if ($index < (count($this->columns)-2))
			{
				if ($id_column != $column->name && ($column->editable != false))
				{
					$column_names[] = $column->name;	
												
					$column_values_new[] = $this->StripTags($column_values[$index]); 
				}
			}
			$index++;
		}
			
		// Plugin code
		while(list($plugin_name, $args) = each($this->database_plugins))
		{
			$file_name = GRID_SOURCE."plugins/class.".$plugin_name.".php";
			if(file_exists($file_name))
			{
				
				require_once($file_name);
				$plugin = $plugin_name."_Plugin";
				$plugin = new $plugin;
				
				list($column_names, $column_values_new) = ($plugin->generateContent($column_names, $column_values_new));
				
			}
		}			
			
		$success = $this->database->Insert($this->sql_table, $column_names, $column_values_new);
		
		$this->database->Disconnect();	
		
		return $success;
	}

	function DeleteRow($column_name, $column_value)
	{
		$this->database->Connect();	
		$this->database->Delete($this->sql_table, $column_name, $column_value);
		$this->database->Disconnect();	
	}
	
	function GetShowExcelIco()
	{
		return $this->show_excel_ico;
	}

	function SetShowExcelIco($show_ico)
	{
		$this->show_excel_ico = $show_ico;
	}
	
	function GetShowPrintIco()
	{
		return $this->show_print_ico;
	}

	function SetShowPrintIco($show_ico)
	{
		$this->show_print_ico = $show_ico;
	}
	
	function GetStripHtmlTags()
	{
		return $this->strip_html_tags;
	}

	function SetStripHtmlTags($strip_tags)
	{
		$this->strip_html_tags = $strip_tags;
	}
	
	function SetUniqueDatabaseColumn($id_column_name, $show)
	{
	    if (array_key_exists($id_column_name, $this->user_defined_columns) == false)
			$this->user_defined_columns[$id_column_name] = new Column($id_column_name);
		
		$this->user_defined_columns[$id_column_name]->is_id = true;
		$this->user_defined_columns[$id_column_name]->show = $show;
	
		$this->column_id = $this->user_defined_columns[$id_column_name];
	}
	
	function SetImageDatabaseColumn($column_name, $image_directory = "")
	{
		
		$args = !empty($image_directory) ? array("image_directory" => $image_directory) : NULL;
		
		$this->SetPlugin($column_name, "image", $args);
	}
	
	function SetUrlDatabaseColumn($column_name, $open_in_new_browser = true)
	{
		$browser = $open_in_new_browser == true ? array("target" => "_blank") : NULL;
		
	    $this->SetPlugin($column_name, "link", $browser);
	}
	
	function SetUrlImageDatabaseColumn($column_name, $open_in_new_browser, $image)
	{
		$browser = $open_in_new_browser == true ? array("target" => "_blank") : NULL;
		
	    $this->SetPlugin($column_name, "link", $browser);
		
		$args = !empty($image_directory) ? array("image_directory" => $image_directory) : NULL;
		
		$this->SetPlugin($column_name, "image", $args);
	}

	function SetMailAdressDatabaseColumn($column_name)
	{
		$this->SetPlugin($column_name, "email");
	}
	
	function SetPlugin($column_name, $plugin, $args = NULL)
	{
		if($column_name == "database")
		{
			$this->database_plugins[$plugin] = $args;
		}
		elseif($column_name == "all")
		{
			$this->all_plugins[$plugin] = $args;
		}
		else
		{
			if (array_key_exists($column_name, $this->user_defined_columns) == false)
				$this->user_defined_columns[$column_name] = new Column($column_name);
			
			$this->user_defined_columns[$column_name]->plugins[$plugin] = $args ;
		}
	}
	
	function SetMaxRowsEachPage($max_rows)
	{
		$this->number_of_rows_each_page	= $max_rows;
	}
	
	function SetEditModeAdd($is_enabled)
	{
		$this->editmode_add	= $is_enabled;
	}
	
	function SetEditModeEdit($is_enabled)
	{
		$this->editmode_edit = $is_enabled;
	}
	
	function SetEditModeDelete($is_enabled)
	{
		$this->editmode_delete = $is_enabled;
	}
	
	function SetTextPage($text_page)
	{
		$this->text_page = $text_page;
	}
	
	function SetTextGoToPage($text_gotopage)
	{
		$this->text_gotopage = $text_gotopage;
	}
	
	function SetTextTotalRows($text_totalrows)
	{
		$this->text_totalrows = $text_totalrows;
	}
	
	function SetTextEdit($text_edit)
	{
		$this->text_edit = $text_edit;
	}

	function SetTextSave($text_save)
	{
		$this->text_save = $text_save;
	}
	
	function SetTextCancel($text_cancel)
	{
		$this->text_cancel = $text_cancel;
	}
	
	function SetTextDelete($text_delete)
	{
		$this->text_delete = $text_delete;
	}
	
	function SetTextAdd($text_add)
	{
		$this->text_add = $text_add;
	}
	
	function SetDatabaseColumnWidth($column_name, $width)
	{
		if ($column_name == "Edit")
			$this->edit_column_width = $width;
		else if ($column_name == "Delete")
			$this->delete_column_width = $width;
		else if (array_key_exists($column_name, $this->user_defined_columns) == false)
			$this->user_defined_columns[$column_name] = new Column($column_name);
		
		$this->user_defined_columns[$column_name]->width = $width;
	}
	
	function SetDatabaseColumnName($old_column_name, $new_column_name)
	{
		if (array_key_exists($old_column_name, $this->user_defined_columns) == false)
			$this->user_defined_columns[$old_column_name] = new Column($old_column_name);
		
		$this->user_defined_columns[$old_column_name]->user_defined_name = $new_column_name;
	}
	
	function SetDatabaseColumnEditable($column_name, $editable)
	{
		if (array_key_exists($column_name, $this->user_defined_columns) == false)
			$this->user_defined_columns[$column_name] = new Column($column_name);
		
		$this->user_defined_columns[$column_name]->editable = $editable;
	}	

	function SetTitleName($sql_table_override)
	{
		$this->sql_table_override = $sql_table_override;
	}
	
	function GetTitleName()
	{
		return $this->sql_table_override;
	}
	
	function ReplaceSpecialSigns($str)
	{
		$index = 0;
		foreach ($this->special_sign_names as $name)
		{
			$str = str_replace($name, $this->special_sign_values[$index], $str);
			$index++;
		}
		return $str;	
	}
	
	function SetSearchStrings($value0, $value1, $value2, $value3, $value4=0)
	{
		$this->search_strings[0] = $value0;
		$this->search_strings[1] = $value1;
		$this->search_strings[2] = $value2;
		$this->search_strings[3] = $value3;
		$this->search_strings[4] = $value4;
	}
	
	function SetOrderColumnIndex($order_column)
	{
		$this->order_column_index = $order_column;
	}
	
}
?>