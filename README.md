#PDFMerger for PHP (PHP 5 Compatible)

PDFMerger created by Jarrod Nettles December 2009 jarrod@squarecrow.com

Updated by Vasiliy Zaytsev February 2016 vasiliy.zaytsev@ffwagency.com

- Uses tcpdf 6.2.12 by Nicola Asuni
- Uses patched tcpdi_parser 1.0 by Paul Nicholls with own TCPdiParserException
- Uses TCPDI 1.0 by Paul Nicholls with FPDF_TPL extension 1.2.3 by Setasign

## PHP 5 Compatible

I have made some changes in original codes to make PHPMerger compatible for PHP 5

## Support of PDF 1.5 and PDF 1.6

FPDF and FPDI libraries replaced by TCPDF with TCPDI extension and parser.

## Instalation

To install the library add the following line to your composer.json:

```"hakimio/pdfmerger": "dev-master"```

### Example Usage
```php
$pdf = new \PDFMerger();

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