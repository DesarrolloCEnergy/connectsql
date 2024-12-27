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
            $fullNameUser=$_REQUEST['fullNameUser'];
            $passwordUser=MD5($_REQUEST['passwordUser']);
            $emailUser=$_REQUEST['emailUser'];
            $phoneUser=$_REQUEST['phoneUser'];
            $companyUser=$_REQUEST['companyUser'];
            $positionUser=$_REQUEST['positionUser'];
            $baseUser = array();
            $eFilterBase = "";
            if(isset($_REQUEST['baseUser']) && is_array($_REQUEST['baseUser'])){
                $baseUser =$_REQUEST['baseUser'];
            } else if(isset($_REQUEST['baseUser'])){
                $baseUser = explode(',',$_REQUEST['baseUser']);
                if(count($baseUser) == 1){
                    $eFilterBase = $_REQUEST['baseUser'];
                }
            }

            $entity->__SET('nameUser', $nameUser);
            $entity->__SET('fullNameUser', $fullNameUser);
            $entity->__SET('passwordUser', $passwordUser);
            $entity->__SET('emailUser', $emailUser);
            $entity->__SET('phoneUser', $phoneUser);
            $entity->__SET('companyUser', $companyUser);
            $entity->__SET('positionUser', $positionUser);
            $entity->__SET('baseUser', json_encode($baseUser));
            $entity->__SET('dateUser', $dateUser);
            $entity->__SET('timeUser', $timeUser);
            
            // $res=$model->UserPosition($positionUser,$eFilterBase);
            //$resultado=array("resultado"=>$res);
            $countgPositionBase = 0;
            foreach ((array)$model->UserPosition($positionUser,$eFilterBase) as $r) :
                $countgPositionBase = $r->__GET('userCountPosition');
            endforeach;
            $eNameUser = $nameUser.$countgPositionBase;
            $entity->__SET('nameUser', $eNameUser);
            $res=$model->UserAdd($entity);
            $resultado=array("resultado"=>$res);
        
            //echo '<script type="text/javascript">alert("Creado exitosamente");</script>';
            //echo "<script> setTimeout(\"location.href='../sheets/vuserlist.php'\",1000)</script>";
            echo "<style>
              .swal2-confirm{
                background-color: #00C4B3 !important;
                color: #fff !important;
              }
            </style>
            <link href='../css/sweetalert2.min.css' rel='stylesheet'>
            <script src='../js/sweetalert2.all.min.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    html: 'Usuario:<br><h1><b>{$eNameUser}</b></h1>Creado exitosamente.',
                    icon: 'success',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    willClose: () => {
                        window.location.href = '../sheets/vuserlist.php';
                    }
                });
                });
            </script>";
        }

?>