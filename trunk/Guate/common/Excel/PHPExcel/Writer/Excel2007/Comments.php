<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2008 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2008 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.6.2, 2008-06-23
 */


/** PHPExcel */
require_once 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
require_once 'PHPExcel/Writer/Excel2007.php';

/** PHPExcel_Writer_Excel2007_WriterPart */
require_once 'PHPExcel/Writer/Excel2007/WriterPart.php';

/** PHPExcel_Worksheet */
require_once 'PHPExcel/Worksheet.php';

/** PHPExcel_Comment */
require_once 'PHPExcel/Comment.php';

/** PHPExcel_RichText */
require_once 'PHPExcel/RichText.php';

/** PHPExcel_Cell */
require_once 'PHPExcel/Cell.php';

/** PHPExcel_Shared_XMLWriter */
require_once 'PHPExcel/Shared/XMLWriter.php';


/**
 * PHPExcel_Writer_Excel2007_Comments
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2008 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Writer_Excel2007_Comments extends PHPExcel_Writer_Excel2007_WriterPart
{
	/**
	 * Write comments to XML format
	 *
	 * @param 	PHPExcel_Worksheet				$pWorksheet
	 * @return 	string 								XML Output
	 * @throws 	Exception
	 */
	public function writeComments(PHPExcel_Worksheet $pWorksheet = null)
	{
		// Create XML writer
		$objWriter = null;
		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK);
		} else {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
		}
			
		// XML header
		$objWriter->startDocument('1.0','UTF-8','yes');
  
  		// Comments cache
  		$comments	= $pWorksheet->getComments();
  		
  		// Authors cache
  		$authors	= array();
  		$authorId	= 0;
		foreach ($comments as $comment) {
			if (!isset($authors[$comment->getAuthor()])) {
				$authors[$comment->getAuthor()] = $authorId++;
			}
		}
  
		// comments
		$objWriter->startElement('comments');
		$objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
			
			// Loop trough authors
			$objWriter->startElement('authors');
			foreach ($authors as $author => $index) {
				$objWriter->writeElement('author', $author);
			}
			$objWriter->endElement();
			
			// Loop trough comments
			$objWriter->startElement('commentList');
			foreach ($comments as $key => $value) {
				$this->_writeComment($objWriter, $key, $value, $authors);
			}
			$objWriter->endElement();
				
		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}
	
	/**
	 * Write comment to XML format
	 *
	 * @param 	PHPExcel_Shared_XMLWriter		$objWriter 			XML Writer
	 * @param	string							$pCellReference		Cell reference
	 * @param 	PHPExcel_Comment				$pComment			Comment
	 * @param	array							$pAuthors			Array of authors
	 * @throws 	Exception
	 */
	public function _writeComment(PHPExcel_Shared_XMLWriter $objWriter = null, $pCellReference = 'A1', PHPExcel_Comment $pComment = null, $pAuthors = null)
	{
		// comment
		$objWriter->startElement('comment');
		$objWriter->writeAttribute('ref', 		$pCellReference);
		$objWriter->writeAttribute('authorId', 	$pAuthors[$pComment->getAuthor()]);
		
			// text
			$objWriter->startElement('text');
			$this->getParentWriter()->getWriterPart('stringtable')->writeRichText($objWriter, $pComment->getText());
			$objWriter->endElement();
		
		$objWriter->endElement();
	}
	
	/**
	 * Write VML comments to XML format
	 *
	 * @param 	PHPExcel_Worksheet				$pWorksheet
	 * @return 	string 								XML Output
	 * @throws 	Exception
	 */
	public function writeVMLComments(PHPExcel_Worksheet $pWorksheet = null)
	{
		// Create XML writer
		$objWriter = null;
		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK);
		} else {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
		}
			
		// XML header
		$objWriter->startDocument('1.0','UTF-8','yes');
  
  		// Comments cache
  		$comments	= $pWorksheet->getComments();
 
		// xml
		$objWriter->startElement('xml');
		$objWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
		$objWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
		$objWriter->writeAttribute('xmlns:x', 'urn:schemas-microsoft-com:office:excel');

			// o:shapelayout
			$objWriter->startElement('o:shapelayout');
			$objWriter->writeAttribute('v:ext', 		'edit');
			
				// o:idmap
				$objWriter->startElement('o:idmap');
				$objWriter->writeAttribute('v:ext', 	'edit');
				$objWriter->writeAttribute('data', 		'1');
				$objWriter->endElement();
			
			$objWriter->endElement();
			
			// v:shapetype
			$objWriter->startElement('v:shapetype');
			$objWriter->writeAttribute('id', 		'_x0000_t202');
			$objWriter->writeAttribute('coordsize', '21600,21600');
			$objWriter->writeAttribute('o:spt', 	'202');
			$objWriter->writeAttribute('path', 		'm,l,21600r21600,l21600,xe');
			
				// v:stroke
				$objWriter->startElement('v:stroke');
				$objWriter->writeAttribute('joinstyle', 	'miter');
				$objWriter->endElement();
				
				// v:path
				$objWriter->startElement('v:path');
				$objWriter->writeAttribute('gradientshapeok', 	't');
				$objWriter->writeAttribute('o:connecttype', 	'rect');
				$objWriter->endElement();
			
			$objWriter->endElement();
		
			// Loop trough comments
			foreach ($comments as $key => $value) {
				$this->_writeVMLComment($objWriter, $key, $value);
			}
				
		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}
	
	/**
	 * Write VML comment to XML format
	 *
	 * @param 	PHPExcel_Shared_XMLWriter		$objWriter 			XML Writer
	 * @param	string							$pCellReference		Cell reference
	 * @param 	PHPExcel_Comment				$pComment			Comment
	 * @throws 	Exception
	 */
	public function _writeVMLComment(PHPExcel_Shared_XMLWriter $objWriter = null, $pCellReference = 'A1', PHPExcel_Comment $pComment = null)
	{
 		// Metadata
 		list($column, $row) = PHPExcel_Cell::coordinateFromString($pCellReference);
 		$column = PHPExcel_Cell::columnIndexFromString($column);
 		$id = 1024 + $column + $row;
 		$id = substr($id, 0, 4);
 		
		// v:shape
		$objWriter->startElement('v:shape');
		$objWriter->writeAttribute('id', 			'_x0000_s' . $id);
		$objWriter->writeAttribute('type', 			'#_x0000_t202');
		$objWriter->writeAttribute('style', 		'position:absolute;margin-left:59.25pt;margin-top:1.5pt;width:96pt;height:55.5pt;z-index:1;visibility:hidden');
		$objWriter->writeAttribute('fillcolor', 	'#ffffe1');
		$objWriter->writeAttribute('o:insetmode', 	'auto');
		
			// v:fill
			$objWriter->startElement('v:fill');
			$objWriter->writeAttribute('color2', 		'#ffffe1');
			$objWriter->endElement();
			
			// v:shadow
			$objWriter->startElement('v:shadow');
			$objWriter->writeAttribute('on', 			't');
			$objWriter->writeAttribute('color', 		'black');
			$objWriter->writeAttribute('obscured', 		't');
			$objWriter->endElement();
		
			// v:path
			$objWriter->startElement('v:path');
			$objWriter->writeAttribute('o:connecttype', 'none');
			$objWriter->endElement();
			
			// v:textbox
			$objWriter->startElement('v:textbox');
			$objWriter->writeAttribute('style', 'mso-direction-alt:auto');
			
				// div
				$objWriter->startElement('div');
				$objWriter->writeAttribute('style', 'text-align:left');
				$objWriter->endElement();
			
			$objWriter->endElement();
			
			// x:ClientData
			$objWriter->startElement('x:ClientData');
			$objWriter->writeAttribute('ObjectType', 'Note');
			
				// x:MoveWithCells
				$objWriter->writeElement('x:MoveWithCells', '');
				
				// x:SizeWithCells
				$objWriter->writeElement('x:SizeWithCells', '');
				
				// x:Anchor
				$objWriter->writeElement('x:Anchor', $column . ', 15, ' . ($row - 2) . ', 10, ' . ($column + 4) . ', 15, ' . ($row + 5) . ', 18');

				// x:AutoFill
				$objWriter->writeElement('x:AutoFill', 'False');
				
				// x:Row
				$objWriter->writeElement('x:Row', ($row - 1));
				
				// x:Column
				$objWriter->writeElement('x:Column', ($column - 1));
			
			$objWriter->endElement();  
		
		$objWriter->endElement();
	}
}