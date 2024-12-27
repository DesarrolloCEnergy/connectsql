<!DOCTYPE html>
<html lang="es">
            <body class="sb-nav-fixed">
                <div id="layoutSidenav">
                    <div id="layoutSidenav_content">
                        <main>
                            <div class="container-fluid px-4">
                                <div class="card mt-4 mb-4">
                                    <div class="card-header">
                                        <h3 class="text-center">Editar usuario</h3>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-10 col-lg-9 col-xl-8">
                                            <div class="card-body">
                                                <form  method="post" accept-charset="utf-8" action="suserupdate.php" onsubmit="sendFormLoading(event);">
                                                    <?php $euid=$_GET["id"]; echo $euid; ?>
                                                    <?php foreach ((array)$model->UserListU($euid) as $r) : ?>
                                                    <?php $aBaseUser = json_decode($r->__GET('baseUser')); ?>
                                                    <input class="form-control" id="idUser" name="idUser" type="text"  value="<?php echo $r->__GET('idUser'); ?>" hidden />
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <select class="form-control" disabled>
                                                                <?php foreach ((array)$model->CompanyList() as $rCompany) : ?>
                                                                    <option value="<?php echo $rCompany->__GET('idCompany'); ?>" <?= ($r->__GET('idCompany') == $rCompany->__GET('idCompany')) ? 'selected' : ''; ?>><?php echo $rCompany->__GET('nameCompany'); ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
                                                                <label for="companyUser">Empresa</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control" type="text" placeholder="Estatus" value="Activo" disabled />
                                                                <label for="nameUser">Estatus</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <select class="form-control" id="positionUser" name="positionUser" disabled>
                                                                    <?php $validateBase = "";?>
                                                                    <?php foreach ((array)$model->PositionList() as $rPosition) : ?>
                                                                        <?php if($r->__GET('positionUser') == $rPosition->__GET('idPosition')){ ?>
                                                                        <?php $validateBase = $rPosition->__GET('typeBasePosition');?>
                                                                        <?php } ?>
                                                                        <option value="<?php echo $rPosition->__GET('idPosition'); ?>" <?= ($r->__GET('positionUser') == $rPosition->__GET('idPosition')) ? 'selected' : '' ;?>><?php echo $rPosition->__GET('namePosition'); ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="positionUser">Puesto</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control" id="nameUser" name="nameUser" type="text" placeholder="Usuario" value="<?php echo $r->__GET('nameUser'); ?>" disabled />
                                                                <label for="nameUser">Usuario</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <?php if($validateBase != 'Multiple'){ ?>
                                                                    <input type="hidden" name="baseUser" value="<?= implode(',',$aBaseUser); ?>">
                                                                <?php } ?>
                                                                <select class="form-control" id="baseUserMultiple" multiple <?= ($validateBase != 'Multiple') ? 'disabled' : 'name="baseUser[]"' ; ?>>
                                                                    <option value="all">Seleccionar Todos</option>
                                                                    <?php $aBase = array();?>
                                                                    <?php foreach ((array)$model->BaseListE() as $rBase) : ?>
                                                                    <?php $aBase[] = $rBase->__GET('idBase');?>
                                                                    <option value="<?php echo $rBase->__GET('idBase'); ?>" <?= (in_array($rBase->__GET('idBase'),$aBaseUser)) ? 'selected' : '' ;?>><?php echo $rBase->__GET('nameBase'); ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="baseUser">Base</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control" id="emailUser" name="emailUser" type="email" placeholder="Correo" value="<?php echo $r->__GET('emailUser'); ?>" />
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
                                                                <input class="form-control" id="fullNameUser" name="fullNameUser" type="text" placeholder="Nombre completo" value="<?php echo $r->__GET('fullNameUser'); ?>" required/>
                                                                <label for="fullNameUser">Nombre completo</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3 mb-md-0">
                                                                <input class="form-control" id="phoneUser" name="phoneUser" type="phones" placeholder="Telefono" value="<?php echo $r->__GET('phoneUser'); ?>" />
                                                                <label for="phoneUser">Telefono</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
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
            });
        </script>
    </body>
</html>
