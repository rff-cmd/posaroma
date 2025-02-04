<?php
@session_start();

if (($_SESSION["logged"] == 0)) {
	echo 'Access denied';
	exit;
}

//include_once ("include/queryfunctions.php");
include_once ("include/sambung.php");
include_once ("include/functions.php");
//include_once ("include/inword.php");

include 'class/class.selectview.php';

$selectview = new selectview;

$petugas		=	$_SESSION["loginname"];
$ref			= 	$_REQUEST['ref'];

$sql = $selectview->list_purchase_return_quick($ref);
$row_purchase_quick=$sql->fetch(PDO::FETCH_OBJ);

$ref			=	$row_purchase_quick->ref;
$vendor_name	=	$row_purchase_quick->vendor_name;
$address_vdr	=	$row_purchase_quick->address;
$phone_vdr		=	$row_purchase_quick->phone;
$date			=	date("d-m-Y", strtotime($row_purchase_quick->date));

$freight_cost 	= 	$row_purchase_quick->freight_cost;
$total 			= 	$row_purchase_quick->total;

$tax_rate		=	$row_purchase_quick->tax_rate;
//$deposit 		= 	$row_purchase_quick->deposit;
$grand_total 	= 	$total; //$total - $deposit;

/*-----------terbilang---------------*/
function get_num_name($num){  
    switch($num){  
        case 1:return 'satu';  
        case 2:return 'dua';  
        case 3:return 'tiga';  
        case 4:return 'empat';  
        case 5:return 'lima';  
        case 6:return 'enam';  
        case 7:return 'tujuh';  
        case 8:return 'delapan';  
        case 9:return 'sembilan';  
    }  
}  

function num_to_words($number, $real_name, $decimal_digit, $decimal_name){  
    $res = '';  
    $real = 0;  
    $decimal = 0;  

    if($number == 0)  
        return 'Nol'.(($real_name == '')?'':' '.$real_name);  
    if($number >= 0){  
        $real = floor($number);  
        $decimal = round($number - $real, $decimal_digit);  
    }else{  
        $real = ceil($number) * (-1);  
        $number = abs($number);  
        $decimal = $number - $real;  
    }  
    $decimal = (int)str_replace('.','',$decimal);  

    $unit_name[1] = 'ribu';  
    $unit_name[2] = 'juta';  
    $unit_name[3] = 'milliar';  
    $unit_name[4] = 'trilliun';  

    $packet = array();  

    $number = strrev($real);  
    $packet = str_split($number,3);  

    for($i=0;$i<count($packet);$i++){  
        $tmp = strrev($packet[$i]);  
        $unit = $unit_name[$i];  
        if((int)$tmp == 0)  
            continue;  
        $tmp_res = '';  
        if(strlen($tmp) >= 2){  
            $tmp_proc = substr($tmp,-2);  
            switch($tmp_proc){  
                case '10':  
                    $tmp_res = 'sepuluh';  
                    break;  
                case '11':  
                    $tmp_res = 'sebelas';  
                    break;  
                case '12':  
                    $tmp_res = 'dua belas';  
                    break;  
                case '13':  
                    $tmp_res = 'tiga belas';  
                    break;  
                case '15':  
                    $tmp_res = 'lima belas';  
                    break;  
                case '20':  
                    $tmp_res = 'dua puluh';  
                    break;  
                case '30':  
                    $tmp_res = 'tiga puluh';  
                    break;  
                case '40':  
                    $tmp_res = 'empat puluh';  
                    break;  
                case '50':  
                    $tmp_res = 'lima puluh';  
                    break;  
                case '70':  
                    $tmp_res = 'tujuh puluh';  
                    break;  
                case '80':  
                    $tmp_res = 'delapan puluh';  
                    break;  
                default:  
                    $tmp_begin = substr($tmp_proc,0,1);  
                    $tmp_end = substr($tmp_proc,1,1);  

                    if($tmp_begin == '1')  
                        $tmp_res = get_num_name($tmp_end).' belas';  
                    elseif($tmp_begin == '0')  
                        $tmp_res = get_num_name($tmp_end);  
                    elseif($tmp_end == '0')  
                        $tmp_res = get_num_name($tmp_begin).' puluh';  
                    else{  
                        if($tmp_begin == '2')  
                            $tmp_res = 'dua puluh';  
                        elseif($tmp_begin == '3')  
                            $tmp_res = 'tiga puluh';  
                        elseif($tmp_begin == '4')  
                            $tmp_res = 'empat puluh';  
                        elseif($tmp_begin == '5')  
                            $tmp_res = 'lima puluh';  
                        elseif($tmp_begin == '6')  
                            $tmp_res = 'enam puluh';  
                        elseif($tmp_begin == '7')  
                            $tmp_res = 'tujuh puluh';  
                        elseif($tmp_begin == '8')  
                            $tmp_res = 'delapan puluh';  
                        elseif($tmp_begin == '9')  
                            $tmp_res = 'sembilan puluh';  

                        $tmp_res = $tmp_res.' '.get_num_name($tmp_end);  
                    }  
                    break;  
            }  

            if(strlen($tmp) == 3){  
                $tmp_begin = substr($tmp,0,1);  
                $space = '';  
                if(substr($tmp_res,0,1) != ' ' && $tmp_res != '')  
                    $space = ' ';  
                if($tmp_begin != 0){  
                    if($tmp_begin == 1)  
                        $tmp_res = 'seratus'.$space.$tmp_res;  
                    else  
                        $tmp_res = get_num_name($tmp_begin).' ratus'.$space.$tmp_res;  
                }  
            }  
        }else  
            $tmp_res = get_num_name($tmp);  

        $space = '';  
        if(substr($res,0,1) != ' ' && $res != '')  
            $space = ' ';  

        if($tmp_res == 'satu' && $unit == 'ribu')  
            $res = 'se'.$unit.$space.$res;  
        else  
            $res = $tmp_res.' '.$unit.$space.$res;  
    }  

    $space = '';  
    if(substr($res,-1) != ' ' && $res != '')  
        $space = ' ';  
    $res .= $space.$real_name;  

    if($decimal > 0)  
        $res .= ' '.num_to_words($decimal, '', 0, '').' '.$decimal_name;  
    return ucfirst($res);  
}  
/*------------------------------------------*/

/*---------print header-----------*/
$sqlunit = $selectview->list_warehouse($_SESSION["location"]);
$dataunit = $sqlunit->fetch(PDO::FETCH_OBJ);

$address = "-";
if(!empty($dataunit->address)) {
	$address = $dataunit->address;	
}

$email = "-";
if(!empty($dataunit->email)) {
	$email = $dataunit->email;
}

$phone = "-";
if(!empty($dataunit->phone)) {
	$phone = $dataunit->phone;
}
/*-------------------------------*/

/*---------print detail----------*/
$data		=	array();
$i 			= 	1;		
$size		= 	500;
$sizeadd 	= 	20;

$sql2 = $selectview->list_purchase_return_quick_detail($ref);
while($row_purchase_quick_detail=$sql2->fetch(PDO::FETCH_OBJ)) {

	$sub_total = $sub_total + $row_purchase_quick_detail->amount;
	$unit_cost = $row_purchase_quick_detail->unit_cost;
	$amount = $row_purchase_quick_detail->amount;
	$total2 = $total2 + $row_purchase_quick_detail->amount;
	
	$item_code	=	$row_purchase_quick_detail->item_code2;
	$qty		=	number_format($row_purchase_quick_detail->qty,"2",".",",");
	$uom_code	=	$row_purchase_quick_detail->uom_code;
	$item_name	=	$row_purchase_quick_detail->item_name;
	$unit_cost	=	number_format($unit_cost,"2",".",",");
	$discount	=	number_format($row_purchase_quick_detail->discount,"2",".",",");
	$discount1	=	number_format($row_purchase_quick_detail->discount1,"2",".",",");
	$amount		=	number_format($amount,"2",".",",");
	 
	//$data[]=$qty.';'.$uom_code.';'.$item_name.';'.$unit_cost.';'.$amount;
	$data[]=$i.'.'.';'.$item_code.';'.$item_name.';'.$uom_code.';'.$qty.';'.$unit_cost.';'.$discount1.';'.$discount.';'.$amount;
	
	$i++;	
	$size = $size + $sizeadd;

}


$total_tax		=	($sub_total * $tax_rate)/100;
 
if($total2 > 0) {
	$total = $total2 + $total_tax;
	$grand_total = $total - $deposit;
}
$terbilang		=	num_to_words($total, 'rupiah', 0, '');
/*-------------------------------------------*/
				



require('pdf/fpdf2.php');
	  	
class PDF extends FPDF
{
	
	var $col=0;
	//Ordinate of column start
	var $y0;
	
	function Header()
	{
		//Page header
		global $address;
		global $phone;
		global $email;
		
		global $ref;
		global $vendor_name;
		global $address_vdr;
		global $date;
		global $phone_vdr;
				
		$this->SetFont('Arial','',9);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0,0,0);
		
		$this->Cell(1,3,'',0,0,'L',false);
		$this->Cell(50,3,'SAHABAT PUTRA',0,1,'L',false);
		//$this->Image('../assets/img/logo.jpg', 10, 5, 25, 20, 'jpg', '');
		$this->Ln(1);
		
		$this->Cell(1,3,'',0,0,'L',false);
		$this->Cell(20,3,'GROSIR & ECERAN',0,1,'L',false);
		$this->Ln(1);
		
		$this->Cell(1,3,'',0,0,'L',false);
		$this->Cell(50,3,'Alamat Kantor : ' . $address . ' Telp./Fax.: ' . $phone,0,1,'L',false);
		$this->Ln(2);
		
		/*$this->Cell(26,3,'',0,0,'L',false);
		$this->Cell(20,3,$email,0,1,'L',false);
		$this->Ln(2);*/
		
		$this->SetFont('Arial','U',9);
		$this->Cell(226,5,'PURCHASE RETURN',0,0,'L',true);
		$this->Cell(50,5,'No : ' . $ref,0,1,'R',false);
		$this->Ln(2);
		
		$this->SetFont('Arial','',9);
		$this->Cell(34,2,'Nama',0,0,'L',true);
		$this->Cell(2,2,':',0,0,'L',false);
		$this->Cell(208,2,$vendor_name,0,0,'L',false);
		$this->Cell(33,2,'Tanggal : ' . $date,0,1,'L',false);
		$this->Ln(2);
		
		$this->Cell(34,2,'Alamat',0,0,'L',true);
		$this->Cell(2,2,':',0,0,'L',false);
		$this->Cell(50,2,$address_vdr,0,1,'L',true);
		$this->Ln(2);
		
		$this->Cell(34,2,'No. Telepon',0,0,'L',true);
		$this->Cell(2,2,':',0,0,'L',false);
		$this->Cell(50,2,$phone_vdr,0,1,'L',true);
		$this->Ln(2);
		
		//Save ordinate
		$this->y0=$this->GetY();
	}
	
	

	var $B;
	var $I;
	var $U;
	var $HREF;
	
	
	function PDF($orientation='l',$unit='mm', $format='a4') 
	{
		//Call parent constructor
		//$size = 300;
		global $size;
		global $sizeadd;
		
		$this->FPDF($orientation,$unit,$format,$size); //$size = tinggi
		//Initialization
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
		
	}

	//Load data
		
	function LoadData($file)
	{		
		//Read file lines
		//$lines=file($file);
		$lines=($file);
		$cekdata = $file[1];
		if( !empty($cekdata) )  {
			foreach($lines as $line) {
				$data[]=explode(';',$line);
			}
		} else {			
			$data[]=explode(';',$file[0]);
			
		}
			
		//foreach($lines as $data)
			//$data[]=explode(';',chop($line));
		return $data;
	} 
	
	
	function BasicTable($header,$data)
	{
		//Header
		$i=0;				
		foreach($header as $col) {
			if ($i==0) { $this->Cell(10,7,$col,1,0,"C"); }
			if ($i==1) { $this->Cell(20,7,$col,1,0,"C"); }
			if ($i==2) { $this->Cell(117,7,$col,1,0,"C"); }
			if ($i==3) { $this->Cell(12,7,$col,1,0,"C"); }
			if ($i==4) { $this->Cell(17,7,$col,1,0,"C"); }
			if ($i==5) { $this->Cell(25,7,$col,1,0,"C"); }
			if ($i==6) { $this->Cell(25,7,$col,1,0,"C"); }
			if ($i==7) { $this->Cell(25,7,$col,1,0,"C"); }
			if ($i==8) { $this->Cell(25,7,$col,1,0,"C"); }
			$i++;
		}
		$this->Ln();
		
		
		//Data		
		foreach($data as $row)
		{	
			$i=0;
			foreach($row as $col) {
				
				if ($i==0) { $this->Cell(10,6,$col,1,0,"C"); }
				if ($i==1) { $this->Cell(20,6,$col,1,0,"L"); }
				if ($i==2) { $this->Cell(117,6,$col,1,0,"L"); }
				if ($i==3) { $this->Cell(12,6,$col,1,0,"C"); }
				if ($i==4) { $this->Cell(17,6,$col,1,0,"R"); }
				if ($i==5) { $this->Cell(25,6,$col,1,0,"R"); }
				if ($i==6) { $this->Cell(25,6,$col,1,0,"R"); }
				if ($i==7) { $this->Cell(25,6,$col,1,0,"R"); }
				if ($i==8) { $this->Cell(25,6,$col,1,0,"R"); }
				$i++;
			}
			$this->Ln();
			
		}	
		
		//-----set sub group
		global $terbilang;
		global $sub_total;
		global $freight_cost;
		global $total_tax;
		global $total;
		global $grand_total;
				
		global $vendor_name;
		global $petugas;
		
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',9);
		
		//$size = $size + $sizeadd;
		$sub_total		=	number_format($sub_total,"2",".",",");
		$total_tax	=	number_format($total_tax,"2",".",",");
		$total			=	number_format($total,"2",".",",");
		$deposit		=	number_format($deposit,"2",".",",");
		$grand_total	=	number_format($grand_total,"2",".",",");
		
		$this->SetFont('Arial','',12);
		$this->Cell(226,5,'',0,0,'L',false); //Terbilang :
		$this->SetFont('Arial','',12);
		$this->Cell(25,5,'Sub Total',0,0,'R',false);
		$this->Cell(25,5,$sub_total,0,1,'R',false);
		/*$this->SetFont('Arial','',12);
		$this->Cell(226,5,'',0,0,'L',true);*/	//$terbilang
		/*$this->SetFont('Arial','',12);
		$this->Cell(25,5,'PPN',0,0,'R',false);
		$this->Cell(25,5,$total_tax,0,1,'R',false);	*/
		
		$this->Cell(226,5,'',0,0,'L',true);	
		$this->Cell(25,5,'Total',0,0,'R',false);
		$this->Cell(25,5,$total,0,1,'R',false);
		
		/*$this->Cell(144,5,'',0,0,'L',true);	
		$this->Cell(25,5,'Uang Muka',0,0,'R',false);
		$this->Cell(25,5,$deposit,0,1,'R',false);
		
		$this->Cell(144,5,'',0,0,'L',true);	
		$this->Cell(25,5,'Sisa',0,0,'R',false);
		$this->Cell(25,5,$grand_total,0,1,'R',false);		
		$this->Ln(5);
		*/
		
		$size = $size + $sizeadd;
		
		
		//-----------
		/*$this->SetFont('Arial','',9);
		$this->Cell(200,2,'- Barang yang sudah dibeli tidak boleh ditukar / dikembalikan, biaya retur 30% dari harga produk',0,1,'L',false);		
		$this->Ln(2);
		
		$size = $size + $sizeadd;
		
		$this->SetFont('Arial','',9);
		$this->Cell(200,2,'- Pembayaran dianggap lunas apabila cek sudah cair / transfer telah kami terima.',0,1,'L',false);		
		$this->Ln(2); */
		
		$this->Ln(2);
		
		$size = $size + $sizeadd;
		
		//---------
		$this->SetFont('Arial','',10);
		$this->Cell(95,5,'Supplier',0,0,'C',true);
		$this->Cell(210,5,'Petugas',0,1,'C',true);	
		$this->Ln(10);
		
		$size = $size + $sizeadd;
		
		$this->Cell(95,5,'( ' . $vendor_name . ' )',0,0,'C',true);	
		$this->Cell(210,5,'( ' . $petugas . ' )',0,1,'C',true);	
		$this->Ln(2);
		
				
	} 
	
	
		
}
//===========================				
$pdf=new PDF();

$title='PURCHASE RETURN';
$pdf->SetTitle($title);	
$pdf->SetTitle($nis);	
$pdf->SetTitle($nama);


//$terbilang = "(" . KalimatUang($total) . ")";
//$pdf->SetTitle($terbilang);

//$total = number_format($total,"0",".",",");
//$total2 = number_format($total2,"0",".",",");
//$pdf->SetTitle($total);
$pdf->SetTitle($size);

/*$G_LOKASI = "Bandung";
$uid = $petugas; //$_SESSION["loginname"];
$tanggalcetak = $G_LOKASI . ", " . $tglcetak;
$getuser = "(". $uid . ")";
*/

$header=array('No.', 'Kode','Nama Barang','Satuan','Qty','Harga','Discount(%)','Discount(Rp)','Total');
//$header2=array('No.','Jenis Biaya','Besarnya');
//Data loading
//$data=$pdf->LoadData('poa.txt');

$data=$pdf->LoadData($data);
//$data2=$pdf->LoadData($data2);
$pdf->SetFont('Arial','',9);
$pdf->AddPage();

//if($jmldata > 0) {
	$pdf->BasicTable($header,$data);
//} 

/*
if($jmldata1 > 0) {
	$pdf->BasicTable2($header2,$data2);
} */

/*$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);*/

$pdf->Output();

?>