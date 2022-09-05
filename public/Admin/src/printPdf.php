<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/libs/connection.php';
require_once __DIR__ . '/auth.php';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->AddPage();
$contents = '';  
$contents .= '
    <h1 align="center">Présence des utilisateurs</h1>
    <table border="1" cellspacing="0" cellpadding="5">  
        <tr style="background-color:#262b59;color:white;">  
            <th width="10%">N°</th>
			<th width="20%">Nom</th>
			<th width="30%">Prénoms</th>
			<th width="25%">Date</th> 
            <th width="15%">Durée de travail</th> 
        </tr>       
    ';  
    
$presences = all_presence();
$nbrResult = number_all_presence();
$duree_de_travail = duration_work_by_day($presences);

$k = 1;
$j = 0;

foreach($presences as $presence){
    if(($k % 6) != 0){
        $contents .= "
        <tr>
            <td>".$k."</td>
            <td>".$presence['nom']."</td>
            <td>".$presence['prenom']."</td>
            <td>".$presence['date']."</td>
            <td>".$duree_de_travail[$j]."</td>
        </tr>
        ";
        
        if ((($k % 5) == 0) ?? ($nbrResult == 1)) {
            $contents .= '</table>';  
            $pdf->writeHTML($contents);
        }

        $k++;
        $j++;
        $nbrResult--;
    }else{
        $pdf->AddPage();
        $contents = '';  
        $contents .= '
            <h1 align="center">Présence des utilisateurs</h1>
            <table border="1" cellspacing="0" cellpadding="5">  
                <tr style="background-color:#262b59;color:white;">  
                    <th width="10%">N°</th>
                    <th width="20%">Nom</th>
                    <th width="30%">Prénoms</th>
                    <th width="25%">Date</th> 
                    <th width="15%">Durée de travail</th> 
                </tr>       
            ';  
            $contents .= "
        <tr>
            <td>".$k."</td>
            <td>".$presence['nom']."</td>
            <td>".$presence['prenom']."</td>
            <td>".$presence['date']."</td>
            <td>".$duree_de_travail[$j]."</td>
        </tr>
        ";
        $k++;
        $j++;
        $nbrResult--;
    }
}

 
$pdf->Output('Rapport.pdf', 'I');
?>