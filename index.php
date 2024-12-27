<?php

require_once 'connec.php';
require_once 'entityl.php';
require_once 'model.php';
require_once 'entity.php';
$connecc = new Connecc();
$entity = new Sesion();
$model = new Model();

?>

<!DOCTYPE html>
<html lang="es">
            <body class="sb-nav-fixed">
                <div id="layoutSidenav">
                    <div id="layoutSidenav_content">
                        <main>
                            <div class="container-fluid px-4">
                                <div class="card mt-4 mb-4">
                                    <div class="card-header">
                                        <h3 class="text-center">Agregar usuario</h3>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-10 col-lg-9 col-xl-8">
                                            <div class="card-body">
                                                <form  method="post" accept-charset="utf-8" enctype="multipart/form-data" action="suseradd.php" onsubmit="sendFormLoading(event);">
                                                    <div class="row mb-3">
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <select class="form-control" id="companyUser" name="companyUser" required>
                                                                    <option value="">Seleccione</option>
                                                                    <?php foreach ((array)$model->CompanyList() as $r) : ?>
                                                                        <option value="<?php echo $r->__GET('idCompany'); ?>"><?php echo $r->__GET('nameCompany'); ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="companyUser">Empresa</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <select class="form-control" id="positionUser" name="positionUser" required>
                                                                    <option value="">Seleccione</option>
                                                                    <?php foreach ((array)$model->PositionList() as $r) : ?>
                                                                            <option value="<?php echo $r->__GET('idPosition'); ?>" validateBase="<?= $r->__GET('typeBasePosition');?>"><?php echo $r->__GET('namePosition'); ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="positionUser">Puesto</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control disOpacy" id="nameUser" name="nameUser" type="text" placeholder="Usuario" required readonly/>
                                                                <label for="nameUser">Usuario</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="content">
                                                        <div class="row mb-3">
                                                            <div class="col-12" id="divBaseUnica">
                                                                <div class="form-floating mb-3 mb-md-0">
                                                                    <select class="form-control" id="baseUserUnica">
                                                                        <option value="">Seleccione</option>
                                                                        <?php foreach ((array)$model->BaseListE() as $r) : ?>
                                                                        <option value="<?php echo $r->__GET('idBase'); ?>"><?php echo $r->__GET('nameBase'); ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <label for="baseUser">Base</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12"  id="divBaseMultiple">
                                                                <div class="form-floating mb-3 mb-md-0">
                                                                    <select class="form-control" id="baseUserMultiple" multiple>
                                                                        <option value="all">Seleccionar Todos</option>
                                                                        <?php $aBase = array();?>
                                                                        <?php foreach ((array)$model->BaseListE() as $r) : ?>
                                                                        <?php $aBase[] = $r->__GET('idBase');?>
                                                                        <option value="<?php echo $r->__GET('idBase'); ?>"><?php echo $r->__GET('nameBase'); ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <label for="baseUser">Base</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12" id="divBaseTodas">
                                                                <input type="hidden" id="baseUserTodas" value="<?= implode(",",$aBase);?>">
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
                                                                <input class="form-control" id="passwordUser" name="passwordUser" type="password" placeholder="Contraseña" />
                                                                <label for="passwordUser">Contraseña</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control" id="fullNameUser" name="fullNameUser" type="text" placeholder="Nombre completo" required/>
                                                                <label for="nameUser">Nombre completo</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control" type="number" id="phoneUser" name="phoneUser" name="phones" placeholder="Telefono" />
                                                                <label for="phoneUser">Telefono</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 mb-0">
                                                        <div class="d-grid"><button type="submit" name="ins" class="btn ColBacVer btn-block btnSubmit">Guardar</button></div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
        <script>
            $(document).ready( function () {
                $('#baseUserUnica').select2();
                $('#baseUserMultiple').select2({
                  placeholder: "Seleccione opciones",
                  allowClear: true
                });
                $('#baseUserMultiple').on('select2:select', function (e) {
                  var data = e.params.data;
                  if (data.id === "all") {
                    var allOptions = $('#baseUserMultiple').find('option:not([value="all"])');
                    allOptions.each(function () {
                      $(this).prop('selected', true);
                    });
                    $('#baseUserMultiple').trigger('change');
                    $('#baseUserMultiple').find('option[value="all"]').prop('selected', false);
                    $('#baseUserMultiple').find('option[value=""]').prop('selected', false);
                    $('#baseUserMultiple').trigger('change.select2');
                  }
                });
                $('#baseUserMultiple').on('select2:unselect', function (e) {
                  var data = e.params.data;
                  if (data.id === "all") {
                    var allOptions = $('#baseUserMultiple').find('option:not([value="all"])');
                    allOptions.each(function () {
                      $(this).prop('selected', false);
                    });
                    $('#baseUserMultiple').trigger('change');
                    $('#baseUserMultiple').find('option[value="all"]').prop('selected', false);
                    $('#baseUserMultiple').find('option[value=""]').prop('selected', false);
                    $('#baseUserMultiple').trigger('change.select2');
                  }
                });
                $('#positionUser').on('change', function() {
                    $('#divBaseUnica').hide();
                    $('#baseUserUnica').removeAttr('name');
                    $('#divBaseMultiple').hide();
                    $('#baseUserMultiple').removeAttr('name');
                    $('#divBaseTodas').hide();
                    $('#baseUserTodas').removeAttr('name');
                    let position = $('#positionUser option:selected');
                    let validateBase = $(position).attr('validateBase');
                    let namePosition = $(position).text();
                    if(validateBase != 'Todas'){
                        //baseUserUnica
                        //baseUserMultiple
                        $('#baseUserUnica').prop('required', false);
                        $('#baseUserMultiple').prop('required', false);
                        $('#baseUser'+validateBase).prop('required', true);
                        if(validateBase == 'Unica'){
                            $('#baseUser'+validateBase).attr('name', 'baseUser');
                        } else {
                            $('#baseUser'+validateBase).attr('name', 'baseUser[]');
                        }
                    } else {
                        $('#baseUserTodas').attr('name', 'baseUser');
                    }
                    $('#divBase'+validateBase).show();
                    if(namePosition == 'Seleccione'){
                        $('#nameUser').val('');
                    } else {
                        var nameAuxPosition = '';
                        nameAuxPosition = namePosition.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        nameAuxPosition = nameAuxPosition.replace(/\s+/g, '');
                        if(validateBase != 'Unica'){
                            nameAuxPosition = nameAuxPosition+'General';
                        }
                        $('#nameUser').val(nameAuxPosition);
                    }
                });
                $('#baseUserUnica').on('change', function() {
                    let base = $('#baseUserUnica option:selected');
                    let nameBase = $(base).text();
                    let position = $('#positionUser option:selected');
                    let namePosition = $(position).text();
                    if(nameBase == 'Seleccione'){
                        $('#nameUser').val('');
                    } else {
                        var nameAuxBase = '';
                        nameAuxBase = nameBase.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        nameAuxBase = nameAuxBase.replace(/\s+/g, '');
                        nameAuxBase = nameAuxBase.toLowerCase();
                        nameAuxBase = nameAuxBase.charAt(0).toUpperCase() + nameAuxBase.slice(1);
                        $('#nameUser').val(namePosition+nameAuxBase);
                    }
                });
                $('#positionUser').trigger('change');
            });
        </script>
    </body>
</html>





<table class="table table-hover table-md">
    <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Telefono</th>
            <th scope="col">Empresa</th>
            <th scope="col">Perfil</th>
            <th scope="col">Inactivar</th>
            <th scope="col">Editar</th>
        </tr>
    </thead>
        <?php foreach ((array)$model->UserList() as $r) : ?>
    <tr>
        <td><?php echo $r->__GET('nameUser'); ?></td>
        <td><?php echo $r->__GET('emailUser'); ?></td>
        <td><?php echo $r->__GET('phoneUser'); ?></td>
        <td><?php echo $r->__GET('nameCompany'); ?></td>
        <td><?php echo $r->__GET('nameProfile'); ?></td>
        <td><a href="suserupdate.php?idi=<?php echo $r->__GET('idUser'); ?>"><i class="fa-regular fa-circle-down fa-lg" style="color: #00c4b3;"></i></a></td>
        <td><a href="vuserupdate.php?id=<?php echo $r->__GET('idUser'); ?>"><img class="icoo1" width="25px" src="../assets/img/editv.png"/></a></td>

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