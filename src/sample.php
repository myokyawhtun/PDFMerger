<?php

use hakimio\PDFMerger;

$pdf = new PDFMerger;

$pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4')
	->addPDF('samplepdfs/two.pdf', '1-2')
	->addPDF('samplepdfs/three.pdf', 'all')
    ->setAuthor('sample author')
    ->setCreator('a sample creator')
    ->setSubject('Merged Attachments')
    ->setKeywords('some sample keywords')
	->merge('file', 'samplepdfs/TEST2.pdf');
