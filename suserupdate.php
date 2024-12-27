<?php
    require_once 'entity.php';
    require_once 'model.php';
    
    $model = new Model();
    $entity = new Enti();

        if(isset($_POST['ins'])){
        
            if(!empty($_REQUEST['passwordUser'])){
                $passwordUser=MD5($_REQUEST['passwordUser']);
                $entity->__SET('passwordUser', $passwordUser);
            }
            $idUser=$_REQUEST['idUser'];
            $emailUser=$_REQUEST['emailUser'];
            $phoneUser=$_REQUEST['phoneUser'];
            @$positionUser=$_REQUEST['positionUser'];
            $fullNameUser=$_REQUEST['fullNameUser'];
            $baseUser = array();
            $eFilterBase = "";
            if(isset($_REQUEST['baseUser']) && is_array($_REQUEST['baseUser'])){
                $baseUser =$_REQUEST['baseUser'];
            } else if(isset($_REQUEST['baseUser'])){
                $baseUser = explode(',',$_REQUEST['baseUser']);
            }
            $entity->__SET('idUser', $idUser);
            $entity->__SET('fullNameUser', $fullNameUser);
            $entity->__SET('emailUser', $emailUser);
            $entity->__SET('phoneUser', $phoneUser);
            //$entity->__SET('positionUser', $positionUser);
            $entity->__SET('baseUser', json_encode($baseUser));
            $res=$model->UserEdit($entity);
            $resultado=array("resultado"=>$res);
        
            //echo '<script type="text/javascript">alert("Editado exitosamente");</script>';
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
                    title: 'Editado exitosamente',
                    icon: 'success',
                    timer: 1000,
                    willClose: () => {
                        window.location.href = 'index.php';
                    }
                });
                });
            </script>";
        } else {
            
            
            
            $idUser=$_GET["idi"];
            $statusUser=2;
            
            $entity->__SET('idUser', $idUser);
            $entity->__SET('statusUser', $statusUser);
            $res=$model->UserInactive($entity);
            $resultado=array("resultado"=>$res);
            
            //echo '<script type="text/javascript">alert("Inactivado exitosamente");</script>';
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
                    title: 'Inactivado exitosamente',
                    icon: 'success',
                    timer: 1000,
                    willClose: () => {
                        window.location.href = 'index.php';
                    }
                });
                });
            </script>";
        }
        

?>