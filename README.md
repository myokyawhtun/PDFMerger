#PDFMerger for PHP (PHP 5 Compatible)

Original written by http://pdfmerger.codeplex.com/team/view

## PHP 5 Compatible

I have made some changes in original codes to make PHPMerger compatible for PHP 5

### Example Usage
```php
include 'PDFMerger.php';

$pdf = new PDFMerger;

$pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4');
$pdf->addPDF('samplepdfs/two.pdf', '1-2');
$pdf->addPDF('samplepdfs/three.pdf', 'all');


$pdf->merge('file', 'samplepdfs/TEST2.pdf'); 
    
// REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
```