<?php 
namespace App\Http\Libraries\PDF;
use \Mpdf\Mpdf;

class PDFM extends Mpdf
{
    public $mpdf;
    public function pdf($html, $array_css = null){
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 40,
            'margin_bottom' => 20,
            'margin_header' => 0,
            'margin_footer' => 0
        ]);
        $mpdf->SetProtection(array('print'));
        // $mpdf->SetTitle("Acme Trading Co. - Invoice");
        // $mpdf->SetAuthor("Acme Trading Co.");
        // $mpdf->SetWatermarkText("Paid");
        // $mpdf->showWatermarkText = true;
        // $mpdf->watermark_font = 'DejaVuSansCondensed';
        // $mpdf->watermarkTextAlpha = 0.1;
        // $mpdf->SetHTMLHeader('<img src="'.url('planning/footer.png').'"/>');

        // $mpdf->SetHTMLFooter('<img src="'.url('planning/footer.png').'"/>');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->use_kwt = true;    // Default: false
        
        if($array_css!=null){
            foreach ($array_css as $link_css){
                $css = file_get_contents($link_css);
                $mpdf->writeHTML($css, 1);
            }
        }
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output();
    }
}