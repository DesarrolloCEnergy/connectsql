<?php
    require_once 'entity.php';
    require_once 'model.php';
    
    $model = new Model();
    $entity = new Enti();


        date_default_timezone_set('America/Mexico_City');
        $sev=date('H');
        $dateUser=date('Y-m-d');
        $timeUser=date($sev.':i:s');

        if(isset($_POST['ins'])){
        
            $nameUser=$_REQUEST['nameUser'];
            $passwordUser=MD5($_REQUEST['passwordUser']);
            $emailUser=$_REQUEST['emailUser'];
            $phoneUser=$_REQUEST['phoneUser'];
            $companyUser=$_REQUEST['companyUser'];
            $profileUser=$_REQUEST['profileUser'];

            $entity->__SET('nameUser', $nameUser);
            $entity->__SET('passwordUser', $passwordUser);
            $entity->__SET('emailUser', $emailUser);
            $entity->__SET('phoneUser', $phoneUser);
            $entity->__SET('companyUser', $companyUser);
            $entity->__SET('profileUser', $profileUser);
            $entity->__SET('dateUser', $dateUser);
            $entity->__SET('timeUser', $timeUser);
            $res=$model->UserAdd($entity);
            $resultado=array("resultado"=>$res);
        
            echo '<script type="text/javascript">alert("Creado exitosamente");</script>';
            echo "<script> setTimeout(\"location.href='../sheets/vuserlist.php'\",1000)</script>";
        }

?>
