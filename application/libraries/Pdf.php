<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }

    protected function ci()
	{
		return get_instance();
	}

	/**
	 * Load a CodeIgniter view into domPDF
	 *
	 * @access	public
	 * @param	string	$view The view to load
	 * @param	array	$data The view data
	 * @return	void
	 */
	public function load_view($view, $data = array())
	{
		$html = $this->ci()->load->view($view, $data, TRUE);
		$this->SetFont('msungstdlight', 'B');
		$this->writeHTML($html, true, false, true, false, '');
	}

	public function Header()
	    {
	    	global $data;
	        	$style = array(

				    'position' => '',

				    'align' => 'C',
				    'stretch' => false,
				    'fitwidth' => true,
				    'cellfitalign' => '',
				    'border' => false,
				    'hpadding' => 'auto',
				    'vpadding' => 'auto',
				    'fgcolor' => array(0,0,0),
				    'bgcolor' => false, //array(255,255,255),
				    'text' => true,
				    'font' => 'msungstdlight',
				    'fontsize' => 8,
				    'stretchtext' => 4
				);
	        $image_file = img_url("/banner.jpg");
	        $this->Image($image_file, 10, 5, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	        // Set font
	        $this->SetFont('msungstdlight', 'B', 20);
	        // Title
	        // $this->Cell(x, y, ' text', 0, false,'x-align' , 0, '', 0, false, 'J', 'B');	        
	        $this->Cell(76, 16, ' 蔡阿姨宿舍租賃合約', 0, false,0 , 0, '', 0, false, 'J', 'B');
	        
	    }
	    public function Footer()
	    {
	        
	        $this->SetY(-15);

	        $this->SetFont('msungstdlight', '', 12);
	        $this->Cell(0, 10, '甲方：                                                                             乙方：', 0, false, 'x-align', 0, 0, 0, false, 'J', 'M');
	        // Set font
	        $this->SetFont('msungstdlight', 'I', 8);
	        // Page number

		
	    }
	
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */

?>
