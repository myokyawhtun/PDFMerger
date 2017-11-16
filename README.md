#PDFMerger for PHP (PHP 5 and above up to PHP 7.1 Compatible)

PDFMerger created by Jarrod Nettles December 2009 jarrod@squarecrow.com

Updated by Vasiliy Zaytsev February 2016 vasiliy.zaytsev@ffwagency.com

- Uses tcpdf 6.2.12 by Nicola Asuni
- Uses patched tcpdi_parser 1.0 by Paul Nicholls with own TCPdiParserException
- Uses TCPDI 1.0 by Paul Nicholls with FPDF_TPL extension 1.2.3 by Setasign

## PHP 5 Compatible

I have made some changes in original codes to make PHPMerger compatible for PHP 5. 

- Update

I tested with PHP 7.1 on my local machine and it still works.

## Support of PDF 1.5 and PDF 1.6

FPDF and FPDI libraries replaced by TCPDF with TCPDI extension and parser.

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