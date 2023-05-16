<?php

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

function generateQR($code = NULL, $folder = NULL, $logo = NULL, $fileName = "qr.png", $labelQR = true)
{
	$writer = new PngWriter();

	// Create QR code
	$qrCode = QrCode::create($code)
		->setEncoding(new Encoding('UTF-8'))
		->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
		->setSize(500)
		->setMargin(10)
		->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
		->setForegroundColor(new Color(0, 0, 0))
		->setBackgroundColor(new Color(255, 255, 255));

	if (!empty($logo)) {
		// Create generic logo
		$fileLogo = $logo['image'];
		$sizeLogo = $logo['size'];

		$logo = Logo::create($fileLogo)
			->setResizeToWidth($sizeLogo);
	}

	// Create generic label
	$label = Label::create($code)
		->setTextColor(new Color(255, 0, 0));

	$result =  $labelQR ? $writer->write($qrCode, $logo, $label) : $writer->write($qrCode, $logo);

	header('Content-Type: ' . $result->getMimeType());

	// Save it to a file
	$result->saveToFile($folder . '/' . $fileName);

	return [
		'qrFolder' => $folder,
		'qrFilename' => $fileName,
		'qrPath' => $folder . '/' . $fileName,
	];
}
