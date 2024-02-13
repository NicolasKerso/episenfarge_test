<?php

include "../connexion.php";

$date=date("Y/m/d");

$heure=gmdate('Y-m-d H:i \G\M\T');


if(trim($_POST['type'])=="Insert")
{
    $date1 = strtr($_POST['StartDate'], '/', '-');
    $dateRend = date('Y-m-d',strtotime($date1));

    // Extraction des dates et heures de $_POST['StartTime'] et $_POST['EndTime1']
    $StartTime = date('D M d Y H:i:s \G\M\T+0000 (e)');
    preg_match("/\d{2}:\d{2}:\d{2}/", $StartTime, $matchesStart);
    $startH = $matchesStart[0];  // L'heure de début extraite

    $EndTime = date('D M d Y H:i:s \G\M\T+0000 (e)');
    preg_match("/\d{2}:\d{2}:\d{2}/", $EndTime, $matchesEnd);
    $endH = $matchesEnd[0];  // L'heure de fin extraite


    $queryC="INSERT INTO rendezvous 

    (
        Id_Pat,
        DateRend,
        StartH,
        EndH,
        TitreRe,
        ObservationRend,
        StartTime,
        EndTime,
        DateIns
    )

    VALUES (
        '".str_replace("'","`",$_POST['Id_Pat'])."',
        '".$dateRend."',
        '".$startH."',
        '".$endH."',
        '".str_replace(",",".",$_POST['title'])."',
        '".str_replace("'","`",$_POST['observation'])."',
        '".$StartTime."',
        '".$EndTime."',
        '".$date."'
    )";
}





else if($_POST['type']=='Delete')
{
	 $queryC="delete  from  rendezvous  where Id_Rend=".$_POST['Id_Rend']."";
}


else if($_POST['type']=='Update')
{

	$queryC="update rendezvous set 


TitreRe='".str_replace("'","`",$_POST['title'])."',
ObservationRend='".str_replace(",",".",$_POST['observation'])."'


where Id_Rend=".$_POST['Id_Rend']."	";



}



echo $queryC;
mysqli_query($con,$queryC);






?>