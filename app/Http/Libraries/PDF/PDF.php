<?php 
namespace App\Http\Libraries\PDF;
use Fpdf\Fpdf;
use DNS2D;
use DNS1D;

class PDF extends Fpdf
{
	var $col = 0;
	protected $T128;                                         // Tableau des codes 128
	protected $ABCset = "";                                  // jeu des caractères éligibles au C128
	protected $Aset = "";                                    // Set A du jeu des caractères éligibles
	protected $Bset = "";                                    // Set B du jeu des caractères éligibles
	protected $Cset = "";                                    // Set C du jeu des caractères éligibles
	protected $SetFrom;                                      // Convertisseur source des jeux vers le tableau
	protected $SetTo;                                        // Convertisseur destination des jeux vers le tableau
	protected $JStart = array("A"=>103, "B"=>104, "C"=>105); // Caractères de sélection de jeu au début du C128
	protected $JSwap = array("A"=>101, "B"=>100, "C"=>99);
	public function __construct(
        $orientation = 'P',
        $unit = 'mm',
        $size = 'A4'
    ) {
        parent::__construct( $orientation, $unit, $size );
        $this->T128[] = array(2, 1, 2, 2, 2, 2);           //0 : [ ]               // composition des caractères
	    $this->T128[] = array(2, 2, 2, 1, 2, 2);           //1 : [!]
	    $this->T128[] = array(2, 2, 2, 2, 2, 1);           //2 : ["]
	    $this->T128[] = array(1, 2, 1, 2, 2, 3);           //3 : [#]
	    $this->T128[] = array(1, 2, 1, 3, 2, 2);           //4 : [$]
	    $this->T128[] = array(1, 3, 1, 2, 2, 2);           //5 : [%]
	    $this->T128[] = array(1, 2, 2, 2, 1, 3);           //6 : [&]
	    $this->T128[] = array(1, 2, 2, 3, 1, 2);           //7 : [']
	    $this->T128[] = array(1, 3, 2, 2, 1, 2);           //8 : [(]
	    $this->T128[] = array(2, 2, 1, 2, 1, 3);           //9 : [)]
	    $this->T128[] = array(2, 2, 1, 3, 1, 2);           //10 : [*]
	    $this->T128[] = array(2, 3, 1, 2, 1, 2);           //11 : [+]
	    $this->T128[] = array(1, 1, 2, 2, 3, 2);           //12 : [,]
	    $this->T128[] = array(1, 2, 2, 1, 3, 2);           //13 : [-]
	    $this->T128[] = array(1, 2, 2, 2, 3, 1);           //14 : [.]
	    $this->T128[] = array(1, 1, 3, 2, 2, 2);           //15 : [/]
	    $this->T128[] = array(1, 2, 3, 1, 2, 2);           //16 : [0]
	    $this->T128[] = array(1, 2, 3, 2, 2, 1);           //17 : [1]
	    $this->T128[] = array(2, 2, 3, 2, 1, 1);           //18 : [2]
	    $this->T128[] = array(2, 2, 1, 1, 3, 2);           //19 : [3]
	    $this->T128[] = array(2, 2, 1, 2, 3, 1);           //20 : [4]
	    $this->T128[] = array(2, 1, 3, 2, 1, 2);           //21 : [5]
	    $this->T128[] = array(2, 2, 3, 1, 1, 2);           //22 : [6]
	    $this->T128[] = array(3, 1, 2, 1, 3, 1);           //23 : [7]
	    $this->T128[] = array(3, 1, 1, 2, 2, 2);           //24 : [8]
	    $this->T128[] = array(3, 2, 1, 1, 2, 2);           //25 : [9]
	    $this->T128[] = array(3, 2, 1, 2, 2, 1);           //26 : [:]
	    $this->T128[] = array(3, 1, 2, 2, 1, 2);           //27 : [;]
	    $this->T128[] = array(3, 2, 2, 1, 1, 2);           //28 : [<]
	    $this->T128[] = array(3, 2, 2, 2, 1, 1);           //29 : [=]
	    $this->T128[] = array(2, 1, 2, 1, 2, 3);           //30 : [>]
	    $this->T128[] = array(2, 1, 2, 3, 2, 1);           //31 : [?]
	    $this->T128[] = array(2, 3, 2, 1, 2, 1);           //32 : [@]
	    $this->T128[] = array(1, 1, 1, 3, 2, 3);           //33 : [A]
	    $this->T128[] = array(1, 3, 1, 1, 2, 3);           //34 : [B]
	    $this->T128[] = array(1, 3, 1, 3, 2, 1);           //35 : [C]
	    $this->T128[] = array(1, 1, 2, 3, 1, 3);           //36 : [D]
	    $this->T128[] = array(1, 3, 2, 1, 1, 3);           //37 : [E]
	    $this->T128[] = array(1, 3, 2, 3, 1, 1);           //38 : [F]
	    $this->T128[] = array(2, 1, 1, 3, 1, 3);           //39 : [G]
	    $this->T128[] = array(2, 3, 1, 1, 1, 3);           //40 : [H]
	    $this->T128[] = array(2, 3, 1, 3, 1, 1);           //41 : [I]
	    $this->T128[] = array(1, 1, 2, 1, 3, 3);           //42 : [J]
	    $this->T128[] = array(1, 1, 2, 3, 3, 1);           //43 : [K]
	    $this->T128[] = array(1, 3, 2, 1, 3, 1);           //44 : [L]
	    $this->T128[] = array(1, 1, 3, 1, 2, 3);           //45 : [M]
	    $this->T128[] = array(1, 1, 3, 3, 2, 1);           //46 : [N]
	    $this->T128[] = array(1, 3, 3, 1, 2, 1);           //47 : [O]
	    $this->T128[] = array(3, 1, 3, 1, 2, 1);           //48 : [P]
	    $this->T128[] = array(2, 1, 1, 3, 3, 1);           //49 : [Q]
	    $this->T128[] = array(2, 3, 1, 1, 3, 1);           //50 : [R]
	    $this->T128[] = array(2, 1, 3, 1, 1, 3);           //51 : [S]
	    $this->T128[] = array(2, 1, 3, 3, 1, 1);           //52 : [T]
	    $this->T128[] = array(2, 1, 3, 1, 3, 1);           //53 : [U]
	    $this->T128[] = array(3, 1, 1, 1, 2, 3);           //54 : [V]
	    $this->T128[] = array(3, 1, 1, 3, 2, 1);           //55 : [W]
	    $this->T128[] = array(3, 3, 1, 1, 2, 1);           //56 : [X]
	    $this->T128[] = array(3, 1, 2, 1, 1, 3);           //57 : [Y]
	    $this->T128[] = array(3, 1, 2, 3, 1, 1);           //58 : [Z]
	    $this->T128[] = array(3, 3, 2, 1, 1, 1);           //59 : [[]
	    $this->T128[] = array(3, 1, 4, 1, 1, 1);           //60 : [\]
	    $this->T128[] = array(2, 2, 1, 4, 1, 1);           //61 : []]
	    $this->T128[] = array(4, 3, 1, 1, 1, 1);           //62 : [^]
	    $this->T128[] = array(1, 1, 1, 2, 2, 4);           //63 : [_]
	    $this->T128[] = array(1, 1, 1, 4, 2, 2);           //64 : [`]
	    $this->T128[] = array(1, 2, 1, 1, 2, 4);           //65 : [a]
	    $this->T128[] = array(1, 2, 1, 4, 2, 1);           //66 : [b]
	    $this->T128[] = array(1, 4, 1, 1, 2, 2);           //67 : [c]
	    $this->T128[] = array(1, 4, 1, 2, 2, 1);           //68 : [d]
	    $this->T128[] = array(1, 1, 2, 2, 1, 4);           //69 : [e]
	    $this->T128[] = array(1, 1, 2, 4, 1, 2);           //70 : [f]
	    $this->T128[] = array(1, 2, 2, 1, 1, 4);           //71 : [g]
	    $this->T128[] = array(1, 2, 2, 4, 1, 1);           //72 : [h]
	    $this->T128[] = array(1, 4, 2, 1, 1, 2);           //73 : [i]
	    $this->T128[] = array(1, 4, 2, 2, 1, 1);           //74 : [j]
	    $this->T128[] = array(2, 4, 1, 2, 1, 1);           //75 : [k]
	    $this->T128[] = array(2, 2, 1, 1, 1, 4);           //76 : [l]
	    $this->T128[] = array(4, 1, 3, 1, 1, 1);           //77 : [m]
	    $this->T128[] = array(2, 4, 1, 1, 1, 2);           //78 : [n]
	    $this->T128[] = array(1, 3, 4, 1, 1, 1);           //79 : [o]
	    $this->T128[] = array(1, 1, 1, 2, 4, 2);           //80 : [p]
	    $this->T128[] = array(1, 2, 1, 1, 4, 2);           //81 : [q]
	    $this->T128[] = array(1, 2, 1, 2, 4, 1);           //82 : [r]
	    $this->T128[] = array(1, 1, 4, 2, 1, 2);           //83 : [s]
	    $this->T128[] = array(1, 2, 4, 1, 1, 2);           //84 : [t]
	    $this->T128[] = array(1, 2, 4, 2, 1, 1);           //85 : [u]
	    $this->T128[] = array(4, 1, 1, 2, 1, 2);           //86 : [v]
	    $this->T128[] = array(4, 2, 1, 1, 1, 2);           //87 : [w]
	    $this->T128[] = array(4, 2, 1, 2, 1, 1);           //88 : [x]
	    $this->T128[] = array(2, 1, 2, 1, 4, 1);           //89 : [y]
	    $this->T128[] = array(2, 1, 4, 1, 2, 1);           //90 : [z]
	    $this->T128[] = array(4, 1, 2, 1, 2, 1);           //91 : [{]
	    $this->T128[] = array(1, 1, 1, 1, 4, 3);           //92 : [|]
	    $this->T128[] = array(1, 1, 1, 3, 4, 1);           //93 : [}]
	    $this->T128[] = array(1, 3, 1, 1, 4, 1);           //94 : [~]
	    $this->T128[] = array(1, 1, 4, 1, 1, 3);           //95 : [DEL]
	    $this->T128[] = array(1, 1, 4, 3, 1, 1);           //96 : [FNC3]
	    $this->T128[] = array(4, 1, 1, 1, 1, 3);           //97 : [FNC2]
	    $this->T128[] = array(4, 1, 1, 3, 1, 1);           //98 : [SHIFT]
	    $this->T128[] = array(1, 1, 3, 1, 4, 1);           //99 : [Cswap]
	    $this->T128[] = array(1, 1, 4, 1, 3, 1);           //100 : [Bswap]                
	    $this->T128[] = array(3, 1, 1, 1, 4, 1);           //101 : [Aswap]
	    $this->T128[] = array(4, 1, 1, 1, 3, 1);           //102 : [FNC1]
	    $this->T128[] = array(2, 1, 1, 4, 1, 2);           //103 : [Astart]
	    $this->T128[] = array(2, 1, 1, 2, 1, 4);           //104 : [Bstart]
	    $this->T128[] = array(2, 1, 1, 2, 3, 2);           //105 : [Cstart]
	    $this->T128[] = array(2, 3, 3, 1, 1, 1);           //106 : [STOP]
	    $this->T128[] = array(2, 1);                       //107 : [END BAR]

	    for ($i = 32; $i <= 95; $i++) {                                            // jeux de caractères
	        $this->ABCset .= chr($i);
	    }
	    $this->Aset = $this->ABCset;
	    $this->Bset = $this->ABCset;
	    
	    for ($i = 0; $i <= 31; $i++) {
	        $this->ABCset .= chr($i);
	        $this->Aset .= chr($i);
	    }
	    for ($i = 96; $i <= 127; $i++) {
	        $this->ABCset .= chr($i);
	        $this->Bset .= chr($i);
	    }
	    for ($i = 200; $i <= 210; $i++) {                                           // controle 128
	        $this->ABCset .= chr($i);
	        $this->Aset .= chr($i);
	        $this->Bset .= chr($i);
	    }
	    $this->Cset="0123456789".chr(206);

	    for ($i=0; $i<96; $i++) {                                                   // convertisseurs des jeux A & B
	        @$this->SetFrom["A"] .= chr($i);
	        @$this->SetFrom["B"] .= chr($i + 32);
	        @$this->SetTo["A"] .= chr(($i < 32) ? $i+64 : $i-32);
	        @$this->SetTo["B"] .= chr($i);
	    }
	    for ($i=96; $i<107; $i++) {                                                 // contrôle des jeux A & B
	        @$this->SetFrom["A"] .= chr($i + 104);
	        @$this->SetFrom["B"] .= chr($i + 104);
	        @$this->SetTo["A"] .= chr($i);
	        @$this->SetTo["B"] .= chr($i);
	    }
        // ...
    }
	function Header($transactionNumber='',$date='',$customer = null)
	{
		// dd($transactionNumber);
		// $this->Write(5,'A set: "'.''.'"');
	    // Logo
	    // $this->setFillColor(227,114,13); 
		// $this->Cell($this->GetPageWidth()-20,1,'',0,0,'L',true);
		$this->Ln(5);
	    $this->Image("https://s3.ap-southeast-1.amazonaws.com/pigijo/assets/logo-black.jpg",10,16.5,25,0,'','https://www.pigijo.id');
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    $this->SetXY($this->GetPageWidth()-70, 25);
	    // Title
	    $this->Cell(60,10,'INVOICE',0,0,'R',false);

		$this->SetXY($this->GetPageWidth()-70,45);
	    $this->SetRightMargin(30);
	    // Line break
	    $this->Ln(10);
	    $this->SetXY($this->GetPageWidth()-70, 35);
	    $this->SetFont('Arial','',12);
	    if($transactionNumber != ''){
	    $this->Cell(60,5,'Transaction No. '.$transactionNumber,0,0,'R');
		}
	    $this->Ln(7);
	    $this->SetFont('Arial','B',15);
	    $this->Cell(50,5,'Customer Details',0,0,'L',false);
	    $this->Cell(80,5);
	    $this->SetFont('Arial','',12);
	    if($date != ''){
	    $this->Cell(60,5,'Date: '.date('d M Y',strtotime($date)),0,0,'R');
	    }

	    $this->Ln(10);
	    $this->customerDetail($customer);
	    $this->setFillColor(0,0,0);
	    if(!empty($transactionNumber)){
	    	$this->Code128($this->GetPageWidth()-60,55,$transactionNumber,50,15);
	    }
	    $this->SetFont('Arial','B',15);
	    $this->Cell(50,10,'Booking Summary');
	    $this->Ln();
	    $header=['Item','Item Description','Qty','Unit Price(Rp)','Total Price(Rp)'];
	    $this->SetFillColor(200,200,200);
	    $this->SetTextColor(0);
	    $this->SetDrawColor(200,200,200);
	    $this->SetLineWidth(.3);
	    $this->SetFont('Arial','',10);
	    // Header
	    $w = array(30, 75, 25, 30,30);
	    for($i=0;$i<count($header);$i++)
	        $this->Cell($w[$i],10,$header[$i],0,0,'L',true);
	    $this->Ln();

	}

	function SetCol($col)
	{
	    // Move position to a column
	    $this->col = $col;
	    $x = 10+$col*65;
	    $this->SetLeftMargin($x);
	    $this->SetX($x);
	}

	function AcceptPageBreak()
	{
	    if($this->col<2)
	    {
	        // Go to next column
	        $this->SetCol($this->col+1);
	        $this->SetY(10);
	        return false;
	    }
	    else
	    {
	        // Go back to first column and issue page break
	        $this->SetCol(0);
	        return true;
	    }
	}

	function customerDetail($customer)
	{
		$this->Cell(35,5,'Name');
		$this->Cell(50,5,': '.(!empty($customer) ? $customer->salutation.'. '.Ucfirst($customer->firstname).' '.$customer->lastname : ''));
		$this->Ln(7);
		$this->Cell(35,5,'Email Address');
		$this->Cell(50,5,': '.(!empty($customer) ? $customer->email : ''));
		$this->Ln(7);
		$this->Cell(35,5,'Phone Number');
		$this->Cell(50,5,': '.(!empty($customer) ? $customer->phone : ''));
		$this->Ln(10);
	}
	// Page footer
	function Footer()
	{
		$this->SetFont('Arial','',12);
		$this->SetY(-27);
		$this->Cell(70,5,'For further inquiries, please contact us');
		$this->Ln(7);
		$this->Cell(70,5,'Phone: +62 8777 288 0924');
		$this->Ln(7);
		$this->Cell(70,5,'Email: info@pigijo.com');
	    $this->Image("https://s3.ap-southeast-1.amazonaws.com/pigijo/assets/logo-black.jpg",$this->GetPageWidth()-35,$this->GetPageHeight()-21.5,25);
	}

	function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

	function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

	function Code128($x, $y, $code, $w, $h) {
	    $Aguid = "";                                                                      // Création des guides de choix ABC
	    $Bguid = "";
	    $Cguid = "";
	    for ($i=0; $i < strlen($code); $i++) {
	        $needle = substr($code,$i,1);
	        $Aguid .= ((strpos($this->Aset,$needle)===false) ? "N" : "O"); 
	        $Bguid .= ((strpos($this->Bset,$needle)===false) ? "N" : "O"); 
	        $Cguid .= ((strpos($this->Cset,$needle)===false) ? "N" : "O");
	    }

	    $SminiC = "OOOO";
	    $IminiC = 4;

	    $crypt = "";
	    while ($code > "") {
	                                                                                    // BOUCLE PRINCIPALE DE CODAGE
	        $i = strpos($Cguid,$SminiC);                                                // forçage du jeu C, si possible
	        if ($i!==false) {
	            $Aguid [$i] = "N";
	            $Bguid [$i] = "N";
	        }

	        if (substr($Cguid,0,$IminiC) == $SminiC) {                                  // jeu C
	            $crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);  // début Cstart, sinon Cswap
	            $made = strpos($Cguid,"N");                                             // étendu du set C
	            if ($made === false) {
	                $made = strlen($Cguid);
	            }
	            if (fmod($made,2)==1) {
	                $made--;                                                            // seulement un nombre pair
	            }
	            for ($i=0; $i < $made; $i += 2) {
	                $crypt .= chr(strval(substr($code,$i,2)));                          // conversion 2 par 2
	            }
	            $jeu = "C";
	        } else {
	            $madeA = strpos($Aguid,"N");                                            // étendu du set A
	            if ($madeA === false) {
	                $madeA = strlen($Aguid);
	            }
	            $madeB = strpos($Bguid,"N");                                            // étendu du set B
	            if ($madeB === false) {
	                $madeB = strlen($Bguid);
	            }
	            $made = (($madeA < $madeB) ? $madeB : $madeA );                         // étendu traitée
	            $jeu = (($madeA < $madeB) ? "B" : "A" );                                // Jeu en cours

	            $crypt .= chr(($crypt > "") ? $this->JSwap[$jeu] : $this->JStart[$jeu]); // début start, sinon swap

	            $crypt .= strtr(substr($code, 0,$made), $this->SetFrom[$jeu], $this->SetTo[$jeu]); // conversion selon jeu

	        }
	        $code = substr($code,$made);                                           // raccourcir légende et guides de la zone traitée
	        $Aguid = substr($Aguid,$made);
	        $Bguid = substr($Bguid,$made);
	        $Cguid = substr($Cguid,$made);
	    }                                                                          // FIN BOUCLE PRINCIPALE

	    $check = ord($crypt[0]);                                                   // calcul de la somme de contrôle
	    for ($i=0; $i<strlen($crypt); $i++) {
	        $check += (ord($crypt[$i]) * $i);
	    }
	    $check %= 103;

	    $crypt .= chr($check) . chr(106) . chr(107);                               // Chaine cryptée complète

	    $i = (strlen($crypt) * 11) - 8;                                            // calcul de la largeur du module
	    $modul = $w/$i;

	    for ($i=0; $i<strlen($crypt); $i++) {                                      // BOUCLE D'IMPRESSION
	        $c = $this->T128[ord($crypt[$i])];
	        for ($j=0; $j<count($c); $j++) {
	            $this->Rect($x,$y,$c[$j]*$modul,$h,"F");
	            $x += ($c[$j++]+$c[$j])*$modul;
	        }
	    }
	}
}