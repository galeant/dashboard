<?php 
namespace App\Http\Libraries\PDF;
use \Mpdf\Mpdf;

class PDFM extends Mpdf
{
    public $mpdf;
    public function __construct() {
        $mpdf = new \Mpdf\Mpdf();
    }
    public function pdf($html, $array_css){
        $mpdf = new \Mpdf\Mpdf();
        foreach ($array_css as $link_css){
            $css = file_get_contents($link_css);
            // dd($css);
            $mpdf->writeHTML($css, 1);
        }
        $mpdf->useDefaultCSS2 = true;
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output();
    }
}