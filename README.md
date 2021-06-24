#PDFMerger for PHP

PDFMerger using `setasign\fpdi`. Compatible with PHP 7+.

## Support of PDF 1.5 and PDF 1.6

FPDF and FPDI libraries replaced by TCPDF with TCPDI extension and parser.

## Instalation

To install the library add the following line to your composer.json:

```bash
composer require hakimio/pdfmerger
```

### Example Usage
```php
use hakimio\PDFMerger;

$pdf = new PDFMerger();

$pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4');
$pdf->addPDF('samplepdfs/two.pdf', '1-2');
$pdf->addPDF('samplepdfs/three.pdf', 'all');

$pdf->setAuthor('sample author');
$pdf->setCreator('a sample creator');
$pdf->setSubject('Merged Attachments');
$pdf->setKeywords('some sample keywords');

$pdf->merge('file', 'samplepdfs/TEST2.pdf'); // generate the file
$pdf->merge('download', 'samplepdfs/test.pdf'); // force download

// REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
```
