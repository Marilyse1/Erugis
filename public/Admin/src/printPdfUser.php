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
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->AddPage();
$contents = '';  
$contents .= '
    <h1 align="center">Liste des utilisateurs</h1>
    <table border="1" cellspacing="0" cellpadding="5">  
    <tr style="background-color:#262b59;color:white;">  
      <th width="7%">N°</th>
      <th width="18%">Nom</th>
      <th width="25%">Prénoms</th>
      <th width="40%">Email</th> 
      <th width="10%">Role</th> 
    </tr>        
    ';  
 
$users = user_list();
$nbrResult = number_user();
$i = 1;

foreach($users as $user){
    if(($i % 4) !== 0){
        $contents .= "
        <tr>
            <td>".$i."</td>
            <td>".$user['nom']."</td>
            <td>".$user['prenom']."</td>
            <td>".$user['email']."</td>
            <td>".$user['role']."</td>
        </tr>
        ";
        if (($nbrResult ==1) || ($i % 5) == 0) {
            $contents .= '</table>';  
            $pdf->writeHTML($contents);
        }
        $i++;
        $nbrResult--;
    }else{
        $pdf->AddPage();
        $contents = '';
        $contents .= '
        <h1 align="center">Liste des utilisateurs</h1>
        <table border="1" cellspacing="0" cellpadding="5">  
         <tr style="background-color:#262b59;color:white;">  
              <th width="7%">N°</th>
              <th width="18%">Nom</th>
              <th width="25%">Prénoms</th>
              <th width="40%">Email</th> 
              <th width="10%">Role</th> 
         </tr>        
        ';  
        $contents .= "
        <tr>
            <td>".$i."</td>
            <td>".$user['nom']."</td>
            <td>".$user['prenom']."</td>
            <td>".$user['email']."</td>
            <td>".$user['role']."</td>
        </tr>
        ";
        $i++;
        $nbrResult--;
    }
    
}

$pdf->Output('listUsers.pdf', 'I');
?>