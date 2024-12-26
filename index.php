<?php

require_once 'connec.php';
require_once 'entityl.php';
require_once 'model.php';
require_once 'entity.php';
$connecc = new Connecc();
$entity = new Sesion();
$model = new Model();

?>

<table class="table table-hover table-md">
    <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Telefono</th>
            <th scope="col">Empresa</th>
            <th scope="col">Perfil</th>

        </tr>
    </thead>
        <?php foreach ((array)$connecc->ConSe() as $r) : ?>
    <tr>
        <td><?php echo $r->__GET('nameUser'); ?></td>
        <td><?php echo $r->__GET('emailUser'); ?></td>
        <td><?php echo $r->__GET('phoneUser'); ?></td>
        <td><?php echo $r->__GET('nameCompany'); ?></td>
        <td><?php echo $r->__GET('nameProfile'); ?></td>

    </tr>
    <?php endforeach; ?>
</table>

<?php

// if($result = $connecc->ConSe() != null){
//         echo "te conectaste";
//     }else{
//         echo "no se pudo";
//     }

?>