<?php
/**
 * @file
 * PDFMerger created in December 2009
 * @author Jarrod Nettles <jarrod@squarecrow.com>
 *
 * Updated by Vasiliy Zaytsev February 2016
 * vasiliy.zaytsev@ffwagency.com
 *
 * @version 2.0
 * This version (comparing to 1.0) supports PDF 1.5 and PDF 1.6.
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
 *
 * @uses tcpdf 6.2.12 by Nicola Asuni
 * @link https://github.com/tecnickcom/TCPDF/tree/master official clone of lib
 * @uses tcpdi_parser 1.0 by Paul Nicholls, patched by own TCPdiParserException
 * @link https://github.com/pauln/tcpdi_parser source of tcpdi_parser.php
 * @uses TCPDI 1.0 by Paul Nicholls with FPDF_TPL extension 1.2.3 by Setasign
 * @link https://github.com/pauln/tcpdi tcpdi.php
 *
 * All of these packages are free and open source software, bundled with this
 * class for ease of use. PDFMerger has all the limitations of the FPDI package
 *  - essentially, it cannot import dynamic content such as form fields, links
 * or page annotations (anything not a part of the page content stream).
 */
namespace PDFMerger;

class PDFMerger
{
	private $_files;	//['form.pdf']  ["1,2,4, 5-19"]
	private $_fpdi;

	/**
	 * Merge PDFs.
	 * @return void
	 */
	public function __construct()
	{
		require_once('tcpdf/tcpdf.php');
		require_once('tcpdf/tcpdi.php');
	}

	/**
	 * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16.
	 * @param $filepath
	 * @param $pages
	 * @return void
	 */
	public function addPDF($filepath, $pages = 'all')
	{
		if(file_exists($filepath))
		{
			if(strtolower($pages) != 'all')
			{
				$pages = $this->_rewritepages($pages);
			}

			$this->_files[] = array($filepath, $pages);
		}
		else
		{
			throw new \exception("Could not locate PDF on '$filepath'");
		}

		return $this;
	}

	/**
	 * Merges your provided PDFs and outputs to specified location.
	 * @param $outputmode
	 * @param $outputname
	 * @return PDF
	 */
	public function merge($outputmode = 'browser', $outputpath = 'newfile.pdf')
	{
		if(!isset($this->_files) || !is_array($this->_files)): throw new exception("No PDFs to merge."); endif;

    $fpdi = new \TCPDI;
    $fpdi->SetPrintHeader(false);
    $fpdi->SetPrintFooter(false);

		//merger operations
		foreach($this->_files as $file)
		{
			$filename  = $file[0];
			$filepages = $file[1];

			$count = $fpdi->setSourceFile($filename);

			//add the pages
			if($filepages == 'all')
			{
				for($i=1; $i<=$count; $i++)
				{
					$template = $fpdi->importPage($i);
					$size = $fpdi->getTemplateSize($template);
					$orientation = ($size['h'] > $size['w']) ? 'P' : 'L';

					$fpdi->AddPage($orientation, array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}
			else
			{
				foreach($filepages as $page)
				{
					if(!$template = $fpdi->importPage($page)): throw new exception("Could not load page '$page' in PDF '$filename'. Check that the page exists."); endif;
					$size = $fpdi->getTemplateSize($template);
					$orientation = ($size['h'] > $size['w']) ? 'P' : 'L';

					$fpdi->AddPage($orientation, array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}
		}

		//output operations
		$mode = $this->_switchmode($outputmode);

		if($mode == 'S')
		{
			return $fpdi->Output($outputpath, 'S');
		}
		else if($mode == 'F')
		{
			$fpdi->Output($outputpath, $mode);
			return true;
		}
		else
		{
			if($fpdi->Output($outputpath, $mode) == '')
			{
				return true;
			}
			else
			{
				throw new exception("Error outputting PDF to '$outputmode'.");
				return false;
			}
		}


	}

	/**
	 * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
	 * @param $mode
	 * @return Character
	 */
	private function _switchmode($mode)
	{
		switch(strtolower($mode))
		{
			case 'download':
				return 'D';
				break;
			case 'browser':
				return 'I';
				break;
			case 'file':
				return 'F';
				break;
			case 'string':
				return 'S';
				break;
			default:
				return 'I';
				break;
		}
	}

	/**
	 * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
	 * @param $pages
	 * @return unknown_type
	 */
	private function _rewritepages($pages)
	{
		$pages = str_replace(' ', '', $pages);
		$part = explode(',', $pages);

		//parse hyphens
		foreach($part as $i)
		{
			$ind = explode('-', $i);

			if(count($ind) == 2)
			{
				$x = $ind[0]; //start page
				$y = $ind[1]; //end page

				if($x > $y): throw new exception("Starting page, '$x' is greater than ending page '$y'."); return false; endif;

				//add middle pages
				while($x <= $y): $newpages[] = (int) $x; $x++; endwhile;
			}
			else
			{
				$newpages[] = (int) $ind[0];
			}
		}

		return $newpages;
	}

}
