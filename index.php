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





<?php include '../includes/import.php'; ?>
<!DOCTYPE html>
<html lang="es">
    <?php include '../includes/zh.php'; ?>
        <?php include '../includes/zt.php'; ?>
            <body class="sb-nav-fixed">
                <div id="layoutSidenav">
                    <?php include '../includes/nleft.php'; ?>
                    <div id="layoutSidenav_content">
                        <main>
                            <div class="container-fluid px-4">
                                <div class="card mt-4 mb-4">
                                    <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if(validateCredential($_SESSION['SidUser'],'add_user')){ ?>
                                            <div class="mt-0 mb-0">
                                                <div class="d-grid"><a href="../sheets/vuseradd.php"><button type="submit" class="btn ColBacVer btn-block">Agregar</button></a></div>
                                            </div>
                                        <?php } ?>
                                        <h3 class="text-center flex-grow-1">Usuarios</h3>
                                    </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="card-body table-responsive text-center">
                                                <table class="table table-hover table-md" id="tbUser">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Nombre</th>
                                                            <th scope="col">Nombre completo</th>
                                                            <th scope="col">Correo</th>
                                                            <th scope="col">Telefono</th>
                                                            <th scope="col">Empresa</th>
                                                            <th scope="col">Puesto</th>
                                                            <th scope="col">Base</th>
                                                            <th scope="col">Inactivar</th>
                                                            <th scope="col">Editar</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>
                        <?php include '../includes/zf.php'; ?>    
                        <?php include '../includes/zd.html'; ?>
                    </div>
                </div>
                <script>
                  $(document).ready( function () {
                    var table = $('#tbUser').DataTable({
                      "language": {
                        "lengthMenu": "Registros por página _MENU_ ",
                        "search": '<span></span> _INPUT_',
                        "searchPlaceholder": 'Buscar:',
                        "zeroRecords": "No se encontró información",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros disponibles",
                        "infoFiltered": "",
                        "scrollX": true,
                        "paginate": {
                          "previous": "Anterior",
                          "next": "Siguiente",
                        },
                        "emptyTable": "No hay datos disponibles en la tabla",
                        "select": {
                            "rows": {
                                "_": "%d filas seleccionadas",
                                "0": "",
                                "1": "1 fila seleccionada"
                            }
                        }
                      },
                      select: true,
                      "serverSide": true,
                      "ajax": {
                        url: "../php/suserlist.php",
                        type: "POST",
                        data: {}
                      },
                      "aaSorting": [[ 0, "asc" ]],
                      "columnDefs": [
                        { searchable: false, targets: [6,7,8] },
                        { orderable: false, targets: [7,8] },
                        {
                          "targets": 7,
                          "data": null,
                          render: function (data, type, row) {
                            var inputTb = "";
                            if(data[9] == '1'){
                                inputTb = "<a href='../php/suserupdate.php?idi="+data[7]+"'><i class='fa-regular fa-circle-down fa-lg' style='color: #00c4b3;'></i></a>";
                            }
                            return inputTb;
                          }
                        },
                        {
                          "targets": 8,
                          "data": null,
                          render: function (data, type, row) {
                            var inputTbEdit = "";
                            if(data[9] == '1'){
                                inputTbEdit = "<a href='../sheets/vuserupdate.php?id="+data[8]+"'><img class='icoo1' width='25px' src='../assets/img/editv.png'/></a>";
                            }
                            return inputTbEdit;
                          }
                        }
                      ]
                    });
                  });
                </script>
            </body>
</html>


<?php

// if($result = $connecc->ConSe() != null){
//         echo "te conectaste";
//     }else{
//         echo "no se pudo";
//     }

?>