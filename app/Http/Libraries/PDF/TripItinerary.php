<?php 
namespace App\Http\Libraries\PDF;
use Fpdf\Fpdf;
use DNS2D;
use DNS1D;

function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['V']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

class TripItinerary extends Fpdf
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

	protected $B;
protected $I;
protected $U;
protected $HREF;
protected $fontList;
protected $issetfont;
protected $issetcolor;
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
		// dd($customer);
		// $this->Write(5,'A set: "'.''.'"');
	    // Logo
	    // $this->setFillColor(227,114,13); 
		// $this->Cell($this->GetPageWidth()-20,1,'',0,0,'L',true);
		// $this->Cell(0,100,'ITINERARY PLANNER',0,0,'R',false);
        $this->SetDrawColor(255,140,0);
        $this->SetLineWidth(20);
        $this->Line(0,0,300,1);
		$this->SetLineWidth(0.1);
        // $this->SetDrawColor(0,0,0);		
		$this->Ln(5);
	    // $this->Image("http://ironman.test/images/logo.png",10,16.5,25,0,'','http://ironman.test');
	    $this->Image("https://s3.ap-southeast-1.amazonaws.com/pigijo/assets/logo-black.jpg",10,16.5,25,0,'','https://www.pigijo.id');
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    $this->SetXY($this->GetPageWidth()-70, 25);
	    // Title
	    $this->Cell(60,10,'TRIP ITINERARY',0,0,'R',false);

		$this->SetXY($this->GetPageWidth()-70,45);
	    // Line break
	    $this->SetFont('Arial','',12);
	    $this->Ln(5);
	    $this->SetXY($this->GetPageWidth()-70, 35);
		$this->Cell(60,5,'nama trip',0,0,'R');
	    $this->Ln(5);
	    $this->SetXY($this->GetPageWidth()-70, 40);
	    $this->Cell(60,5,'tanggal trip',0,0,'R');
		$this->Ln(7);

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
		$this->SetDrawColor(255,140,0);
        $this->SetLineWidth(10);
        $this->Line(0,299,300,299);
		$this->SetLineWidth(0.1);
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
	function Circle($x, $y, $r, $style='DF')
	{
		$this->Ellipse($x,$y,$r,$r,$style);
	}

	function Ellipse($x, $y, $rx, $ry, $style='DF')
	{
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$lx=4/3*(M_SQRT2-1)*$rx;
		$ly=4/3*(M_SQRT2-1)*$ry;
		$k=$this->k;
		$h=$this->h;
		$this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
			($x+$rx)*$k,($h-$y)*$k,
			($x+$rx)*$k,($h-($y-$ly))*$k,
			($x+$lx)*$k,($h-($y-$ry))*$k,
			$x*$k,($h-($y-$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$lx)*$k,($h-($y-$ry))*$k,
			($x-$rx)*$k,($h-($y-$ly))*$k,
			($x-$rx)*$k,($h-$y)*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$rx)*$k,($h-($y+$ly))*$k,
			($x-$lx)*$k,($h-($y+$ry))*$k,
			$x*$k,($h-($y+$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
			($x+$lx)*$k,($h-($y+$ry))*$k,
			($x+$rx)*$k,($h-($y+$ly))*$k,
			($x+$rx)*$k,($h-$y)*$k,
			$op));
	}
	function Circle_Start($w, $h, $x, $y){
		$this->SetFillColor(255,255,255);
		$this->SetDrawColor(255,140,0);
		$this->Ellipse($w, $h, $x+1, $y+1);
		$this->SetFillColor(255,140,0);
	    $this->Ellipse($w, $h, $x, $y);
	    $this->SetFillColor(200,200,200);
	    $this->SetTextColor(0);
	    $this->SetDrawColor(200,200,200);
	}
	function Circle_Activity($w, $h, $x, $y){
		$this->SetDrawColor(255,140,0);
		$this->SetFillColor(255,140,0);
	    $this->Ellipse($w, $h, $x, $y);
	    $this->SetFillColor(200,200,200);
	    $this->SetTextColor(0);
	    $this->SetDrawColor(200,200,200);
	}
	function WordWrap($text, $maxwidth)
	{
		$text = trim($text);
		if ($text==='')
			return 0;
		$space = $this->GetStringWidth(' ');
		$lines = explode("\n", $text);
		$text = '';
		$count = 0;

		foreach ($lines as $line)
		{
			$words = preg_split('/ +/', $line);
			$width = 0;

			foreach ($words as $word)
			{
				$wordwidth = $this->GetStringWidth($word);
				if ($wordwidth > $maxwidth)
				{
					// Word is too long, we cut it
					for($i=0; $i<strlen($word); $i++)
					{
						$wordwidth = $this->GetStringWidth(substr($word, $i, 1));
						if($width + $wordwidth <= $maxwidth)
						{
							$width += $wordwidth;
							$text .= substr($word, $i, 1);
						}
						else
						{
							$width = $wordwidth;
							$text = rtrim($text)."\n".substr($word, $i, 1);
							$count++;
						}
					}
				}
				elseif($width + $wordwidth <= $maxwidth)
				{
					$width += $wordwidth + $space;
					$text .= $word.' ';
				}
				else
				{
					$width = $wordwidth + $space;
					$text = rtrim($text)."\n".$word.' ';
					$count++;
				}
			}
			$text = rtrim($text)."\n";
			$count++;
		}
		$text = rtrim($text);
		return $count;
	}

	function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
	}
	
	function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true)
	{
		$xi=$this->GetX();
		$yi=$this->GetY();
		
		$hrow=$this->FontSize;
		$textrows=$this->drawRows($w,$hrow,$strText,0,$align,0,0,0);
		$maxrows=floor($h/$this->FontSize);
		$rows=min($textrows,$maxrows);

		$dy=0;
		if (strtoupper($valign)=='M')
			$dy=($h-$rows*$this->FontSize)/2;
		if (strtoupper($valign)=='B')
			$dy=$h-$rows*$this->FontSize;

		$this->SetY($yi+$dy);
		$this->SetX($xi);

		$this->drawRows($w,$hrow,$strText,0,$align,false,$rows,1);

		if ($border)
			$this->Rect($xi,$yi,$w,$h);
	}

	function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
	{
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
		$b=0;
		if($border)
		{
			if($border==1)
			{
				$border='LTRB';
				$b='LRT';
				$b2='LR';
				
			}
			else
			{
				$b2='';
				if(is_int(strpos($border,'L')))
					$b2.='L';
				if(is_int(strpos($border,'R')))
					$b2.='R';
				$b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
			}
		}
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$ns=0;
		$nl=1;
		while($i<$nb)
		{
			//Get next character
			$c=$s[$i];
			if($c=="\n")
			{
				//Explicit line break
				if($this->ws>0)
				{
					$this->ws=0;
					if ($prn==1) $this->_out('0 Tw');
				}
				if ($prn==1) {
					$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				}
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
				if ( $maxline && $nl > $maxline )
					return substr($s,$i);
				continue;
			}
			if($c==' ')
			{
				$sep=$i;
				$ls=$l;
				$ns++;
			}
			$l+=$cw[$c];
			if($l>$wmax)
			{
				//Automatic line break
				if($sep==-1)
				{
					if($i==$j)
						$i++;
					if($this->ws>0)
					{
						$this->ws=0;
						if ($prn==1) $this->_out('0 Tw');
					}
					if ($prn==1) {
						$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
					}
				}
				else
				{
					if($align=='J')
					{
						$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
						if ($prn==1) $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
					}
					if ($prn==1){
						$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
					}
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
				if ( $maxline && $nl > $maxline )
					return substr($s,$i);
			}
			else
				$i++;
		}
		//Last chunk
		if($this->ws>0)
		{
			$this->ws=0;
			if ($prn==1) $this->_out('0 Tw');
		}
		if($border && is_int(strpos($border,'B')))
			$b.='B';
		if ($prn==1) {
			$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
		}
		$this->x=$this->lMargin;
		return $nl;
	}
	

	function WriteHTML($html)
{
    //HTML parser
    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
    $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,stripslashes(txtentities($e)));
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    //Opening tag
    switch($tag){
        case 'STRONG':
            $this->SetStyle('B',true);
            break;
        case 'EM':
            $this->SetStyle('I',true);
            break;
        case 'B':
        case 'I':
        case 'U':
            $this->SetStyle($tag,true);
            break;
        case 'A':
            $this->HREF=$attr['HREF'];
            break;
        case 'IMG':
            if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;
                $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
            }
            break;
        case 'TR':
        case 'BLOCKQUOTE':
        case 'BR':
            $this->Ln(5);
            break;
        case 'P':
            $this->Ln(10);
            break;
        case 'FONT':
            if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                $coul=hex2dec($attr['COLOR']);
                $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                $this->issetcolor=true;
            }
            if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                $this->SetFont(strtolower($attr['FACE']));
                $this->issetfont=true;
            }
            break;
    }
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='STRONG')
        $tag='B';
    if($tag=='EM')
        $tag='I';
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
    if($tag=='FONT'){
        if ($this->issetcolor==true) {
            $this->SetTextColor(0);
        }
        if ($this->issetfont) {
            $this->SetFont('arial');
            $this->issetfont=false;
        }
    }
}
function SetStyle($tag, $enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
function SetCellMargin($margin){
	// Set cell margin
	$this->cMargin = $margin;
}

}