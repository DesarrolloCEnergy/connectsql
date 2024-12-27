<?php

require_once 'connec.php';
require_once 'entityl.php';
require_once 'model.php';
require_once 'entity.php';
$connecc = new Connecc();
$entity = new Sesion();
$model = new Model();

?>

<div class="row justify-content-center">
    <div class="col-sm-12 col-md-10 col-lg-9 col-xl-8">
        <div class="card-body">
            <form  method="post" accept-charset="utf-8" enctype="multipart/form-data" action="suseradd.php">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="nameUser" name="nameUser" type="text" placeholder="Nombre" />
                            <label for="nameUser">Nombre</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="passwordUser" name="passwordUser" type="password" placeholder="Contraseña" />
                            <label for="passwordUser">Contraseña</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="emailUser" name="emailUser" type="email" placeholder="Correo" />
                            <label for="emailUser">Correo</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="phoneUser" name="phoneUser" name="phones" placeholder="Telefono" />
                            <label for="phoneUser">Telefono</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <select class="form-control" id="companyUser" name="companyUser">
                            <?php foreach ((array)$model->CompanyList() as $r) : ?>
                                <option value="<?php echo $r->__GET('idCompany'); ?>"><?php echo $r->__GET('nameCompany'); ?></option>
                            <?php endforeach; ?>
                            </select>
                            <label for="companyUser">Empresa</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <select class="form-control" id="profileUser" name="profileUser">
                            <?php foreach ((array)$model->ProfileList() as $r) : ?>
                                <option value="<?php echo $r->__GET('idProfile'); ?>"><?php echo $r->__GET('nameProfile'); ?></option>
                            <?php endforeach; ?>
                            </select>
                            <label for="profileUser">Perfil</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 mb-0">
                    <div class="d-grid"><button type="submit" name="ins" class="btn ColBacVer btn-block">Guardar</button></div>
                </div>
            </form>
        </div>
    </div>
</div>



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