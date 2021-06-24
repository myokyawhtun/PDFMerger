<?php
/**
 * @file
 * PDFMerger created in December 2009
 * @author Jarrod Nettles <jarrod@squarecrow.com>
 *
 * Updated by Tomas Rimkus
 *
 *
 * Class for easily merging PDFs (or specific pages of PDFs) together into one.
 * Output to a file, browser, download, or return as a string. Unfortunately,
 * this class does not preserve many of the enhancements your original PDF
 * might contain. It treats your PDF page as an image and then concatenates
 * them all together.
 *
 * Note that your PDFs are merged in the order that you provide them using the
 * addPDF function, same as the pages. If you put pages 12-14 before 1-5 then
 * 12-15 will be placed first in the output.
 */

namespace hakimio;

use Exception;
use setasign\Fpdi\Fpdi;

class PDFMerger
{
    private $_files;    //['form.pdf']  ["1,2,4, 5-19"]

    private $_temp_filenames = [];

    private $_author;
    private $_creator;
    private $_subject;
    private $_title;
    private $_keywords;

    private $_zoom = 100;
    private $_page_layout = 'continuous';

    /**
     * @return float
     */
    public function getZoom()
    {
        return $this->_zoom;
    }

    /**
     * @param float $zoom page zoom (1.0 - 100.0) Default: 100
     */
    public function setZoom($zoom)
    {
        $this->_zoom = $zoom;
    }

    /**
     * @return string
     */
    public function getPageLayout()
    {
        return $this->_page_layout;
    }

    /**
     * @param string $page_layout Possible values: SinglePage, OneColumn, TwoColumnLeft, TwoColumnRight, TwoPageLeft, TwoPageRight. Default: OneColumn. For more info see SetDisplayMode() method in TCPDF class
     */
    public function setPageLayout($page_layout)
    {
        $this->_page_layout = $page_layout;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * @param string $author
     * @return PDFMerger
     */
    public function setAuthor($author)
    {
        $this->_author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreator()
    {
        return $this->_creator;
    }

    /**
     * @param string $creator
     * @return PDFMerger
     */
    public function setCreator($creator)
    {
        $this->_creator = $creator;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @param string $subject
     * @return PDFMerger
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param string $title
     * @return PDFMerger
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->_keywords;
    }

    /**
     * @param string $keywords
     * @return PDFMerger
     */
    public function setKeywords($keywords)
    {
        $this->_keywords = $keywords;
        return $this;
    }

    public function __destruct()
    {
        $this->cleanTempFiles();
    }

    /**
     * Add a PDF as a string for inclusion in the merge. Pages should be formatted: 1,3,6, 12-16.
     * @param string $pdfString
     * @param string $pages
     * @return PDFMerger
     * @throws Exception
     */
    public function addPdfString($pdfString, $pages = 'all')
    {
        $tempName = tempnam(sys_get_temp_dir(), 'PDFMerger');

        if (@file_put_contents($tempName, $pdfString) === false)
            throw new Exception("Unable to create temporary file");

        $this->_temp_filenames[] = $tempName;

        return $this->addPDF($tempName, $pages);
    }

    /**
     * Delete the temporary files created for the merge
     * @return void
     */
    public function cleanTempFiles()
    {
        foreach ($this->_temp_filenames as $tempFile)
            @unlink($tempFile);

        $this->_temp_filenames = [];
    }

    /**
     * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16.
     * @param $filepath
     * @param string $pages
     * @return PDFMerger
     * @throws Exception
     */
    public function addPDF($filepath, $pages = 'all')
    {
        if (file_exists($filepath)) {
            if (strtolower($pages) != 'all') {
                $pages = $this->_rewritepages($pages);
            }

            $this->_files[] = array($filepath, $pages);
        } else {
            throw new Exception("Could not locate PDF on '$filepath'");
        }

        return $this;
    }

    /**
     * Merges your provided PDFs and outputs to specified location.
     * @param string $outputmode
     * @param string $outputpath
     * @return string|boolean
     * @throws Exception
     * @internal param $outputname
     */
    public function merge($outputmode = 'browser', $outputpath = 'newfile.pdf')
    {
        if (!isset($this->_files) || !is_array($this->_files))
            throw new Exception("No PDFs to merge.");

        $fpdi = new Fpdi();

        //merger operations
        foreach ($this->_files as $file) {
            $filename = $file[0];
            $filepages = $file[1];

            $count = $fpdi->setSourceFile($filename);

            //add the pages
            if ($filepages == 'all') {
                for ($i = 1; $i <= $count; $i++) {
                    $template = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($template);
                    $orientation = ($size['height'] > $size['width']) ? 'P' : 'L';

                    $fpdi->AddPage($orientation, array($size['width'], $size['height']));
                    $fpdi->useTemplate($template);
                }
            } else {
                foreach ($filepages as $page) {
                    if (!$template = $fpdi->importPage($page))
                        throw new Exception("Could not load page '$page' in PDF '$filename'. Check that the page exists.");

                    $size = $fpdi->getTemplateSize($template);
                    $orientation = ($size['height'] > $size['width']) ? 'P' : 'L';

                    $fpdi->AddPage($orientation, array($size['width'], $size['height']));
                    $fpdi->useTemplate($template);
                }
            }
        }

        // set metadata, if any
        if (isset($this->_author))
            $fpdi->SetAuthor($this->_author);

        if (isset($this->_creator))
            $fpdi->SetCreator($this->_creator);

        if (isset($this->_subject))
            $fpdi->SetSubject($this->_subject);

        if (isset($this->_title))
            $fpdi->SetTitle($this->_title);

        if (isset($this->_keywords))
            $fpdi->SetKeywords($this->_keywords);

        $fpdi->SetDisplayMode($this->_zoom, $this->_page_layout);

        //output operations
        $mode = $this->_switchmode($outputmode);

        if ($mode == 'S') {
            return $fpdi->Output( 'S', $outputpath);
        } else if ($mode == 'F') {
            $fpdi->Output($mode, $outputpath);
            return true;
        } else {
            if ($fpdi->Output($mode, $outputpath) == '') {
                return true;
            } else {
                throw new Exception("Error outputting PDF to '$outputmode'.");
            }
        }
    }

    /**
     * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
     * @param $mode
     * @return string
     */
    private function _switchmode($mode)
    {
        switch (strtolower($mode)) {
            case 'download':
                return 'D';
            case 'file':
                return 'F';
            case 'string':
                return 'S';
            case 'browser':
            default:
                return 'I';
        }
    }

    /**
     * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
     * @param $pages
     * @return array
     * @throws Exception
     */
    private function _rewritepages($pages)
    {
        $pages = str_replace(' ', '', $pages);
        $part = explode(',', $pages);
        $newpages = [];

        //parse hyphens
        foreach ($part as $i) {
            $ind = explode('-', $i);

            if (count($ind) == 2) {
                $x = $ind[0]; //start page
                $y = $ind[1]; //end page

                if ($x > $y)
                    throw new Exception("Starting page, '$x' is greater than ending page '$y'.");

                //add middle pages
                while ($x <= $y): $newpages[] = (int)$x;
                    $x++; endwhile;
            } else {
                $newpages[] = (int)$ind[0];
            }
        }

        return $newpages;
    }

}
