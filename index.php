<?php

require_once 'connec.php';
require_once 'entityl.php';
require_once 'model.php';
require_once 'entity.php';
$connecc = new Connecc();
$entity = new Sesion();
$model = new Model();

// foreach ((array)$connecc->ConSe() as $r) :
//     echo $r->__GET('nameUser');
//     echo $r->__GET('emailUser');
//     echo $r->__GET('phoneUser');
//     echo $r->__GET('nameCompany');
//     echo $r->__GET('nameProfile');
// endforeach;

if($result = $connecc->ConSe() != null){
        echo "te conectaste";
    }else{
        echo "no se pudo";
    }

?>