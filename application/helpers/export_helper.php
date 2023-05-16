<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

use Dompdf\Dompdf;
use Dompdf\Options;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// function generate_tcpdf($dataToPrint, $option = NULL, $type = 1)
// {
//     ini_set('display_errors', '1');
//     ob_end_clean();
//     ini_set('memory_limit', '2048M');
//     ini_set('max_execution_time', 0);

//     ob_start();

//     // create new PDF document
//     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//     // set document information
//     $pdf->SetCreator(PDF_CREATOR);
//     $pdf->SetAuthor(empty($option) ? 'MOHD FAHMY IZWAN BIN ZULKHAFRI' : $option['author']);
//     $pdf->SetTitle(empty($option) ? 'ARCA EVENT PDF' : $option['title']);

//     // set header and footer fonts
//     // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//     // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//     // set default monospaced font
//     $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//     // set margins
//     $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//     // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//     // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//     // set auto page breaks
//     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//     // set image scale factor
//     $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//     // set some language-dependent strings (optional)
//     if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
//         require_once(dirname(__FILE__) . '/lang/eng.php');
//         $pdf->setLanguageArray($l);
//     }

//     // ---------------------------------------------------------

//     // set font
//     $pdf->SetFont('times', '', 11);

//     // Print a table

//     // add a page
//     $pdf->AddPage();

//     // output the HTML content
//     $pdf->writeHTML($dataToPrint, true, false, true, false, '');

//     // Clean any content of the output buffer
//     ob_end_clean();

//     $filename = empty($option) ? 'report.pdf' : $option['filename'];

//     // Close and output PDF document
//     $result = $pdf->Output($filename, 'I');

//     if ($result) {
//         return ['resCode' => 200, 'message' => 'Export to PDF', 'result' => $result];
//     } else {
//         return ['resCode' => 400, 'message' => "Can't generate PDF"];
//     }
// }
if (!function_exists('generate_dompdf')) {
	function generate_dompdf($dataToPrint, $option = NULL)
	{
		$author = empty($option) ? "CANTHINK SOLUTION" : (isset($option['author']) ? $option['author'] : NULL);
		$title = empty($option) ? "REPORT PDF" : (isset($option['title']) ? $option['title'] : "REPORT PDF");
		$filename = empty($option) ? "report" : (isset($option['filename']) ? $option['filename'] : "report");
		$paper = empty($option) ? "A4" : (isset($option['paper']) ? $option['paper'] : "A4");
		$orientation = empty($option) ? "portrait" : (isset($option['orientation']) ? $option['orientation'] : "portrait");
		$download = empty($option) ? TRUE : (isset($option['download']) ? $option['download'] : TRUE);

		ob_end_clean(); // reset previous buffer
		ini_set('display_errors', '1');
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', 0);

		// start output buffering
		ob_start();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($dataToPrint);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper($paper, $orientation);

		// Render the HTML as PDF
		$dompdf->render();

		$dompdf->addInfo('Title', $title);
		$dompdf->addInfo('Author', $author);

		// Output the generated PDF to Browser
		if ($download)
			$result = $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
		else
			$result = $dompdf->stream($filename . '.pdf', array('Attachment' => 0));

		// end output buffering and flush the output
		ob_end_clean();
	}
}

if (!function_exists('exportToExcel')) {
	function exportToExcel($data, $filename = "data.xlsx")
	{
		ini_set('display_errors', '1');
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', 0);

		try {
			// reset previous buffer
			ob_end_clean();

			// start output buffering
			ob_start();

			// Create new Spreadsheet object
			$spreadsheet = new Spreadsheet();

			// set properties
			$title = empty($option) ? "My Excel Data" : (isset($option['title']) ? $option['title'] : "My Excel Data");
			$spreadsheet->getProperties()
				->setTitle($title)
				->setKeywords('data,export,excel')
				->setCreator('ARCA SYSTEM')
				->setLastModifiedBy(currentUserFullName())
				->setCompany(currentUserBranchName())
				->setCategory('Data Export')
				->setCreated(timestamp());

			// Add data to the first sheet
			$sheet = $spreadsheet->getActiveSheet();

			// Set data in the worksheet
			$sheet->fromArray($data);

			// Set the headers to force a download
			// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');

			// Create a new Xlsx writer and save the file
			$writer = new Xlsx($spreadsheet);

			// Check if the writer object is valid
			if ($writer === null) {
				return ['resCode' => 400, 'message' => 'Error creating Xlsx writer object'];
			}

			// end output buffering and flush the output
			ob_end_clean();

			$directory = 'public' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR;
			if (!file_exists($directory)) {
				mkdir($directory, 0755, true);
			}

			$tempFile = $directory . 'export_excel.xls';
			if (file_exists($directory)) {
				unlink($tempFile);
			}

			$result = $writer->save($tempFile);

			// Save to computer.
			// $result = $writer->save('php://output');

			// Check if the file was saved successfully
			// if ($result === null) {
			// 	return ['resCode' => 400, 'message' => 'Error saving Excel file'];
			// 	exit;
			// }

			// Return success message
			return ['resCode' => 200, 'message' => 'File exported', 'filename' => $filename, 'path' => url($tempFile)];
		} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
			return ['resCode' => 400, 'message' => 'Error writing to file: ', $e->getMessage()];
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			return ['resCode' => 400, 'message' => 'Error: ', $e->getMessage()];
		} catch (Exception $e) {
			// Return error message
			return ['resCode' => 400, 'message' => 'Error exporting file: ' . $e->getMessage()];
		}
	}
}
