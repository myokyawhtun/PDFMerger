#PDFMerger for PHP (PHP 5 Compatible)

PDFMerger created by Jarrod Nettles December 2009 jarrod@squarecrow.com

- Uses FPDI 1.3.1 from Setasign
- Uses FPDF 1.6 by Olivier Plathey with FPDF_TPL extension 1.1.3 by Setasign

## PHP 5 Compatible

I have made some changes in original codes to make PHPMerger compatible for PHP 5

### Example Usage
```php
include 'PDFMerger.php';

$pdf = new PDFMerger; // or use $pdf = new \PDFMerger; for Laravel

$pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4');
$pdf->addPDF('samplepdfs/two.pdf', '1-2');
$pdf->addPDF('samplepdfs/three.pdf', 'all');


$pdf->merge('file', 'samplepdfs/TEST2.pdf'); // generate the file

$pdf->merge('download', 'samplepdfs/test.pdf'); // force download 

// REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
```