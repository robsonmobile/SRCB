<?php
session_start();
include '../library/configServer.php';
include '../library/consulSQL.php';
$codeTeacher=consultasSQL::CleanStringText($_POST['code']);
$selectTeacher=ejecutarSQL::consultar("SELECT * FROM docente WHERE DUI='".$codeTeacher."'");
$dataTeacher=mysql_fetch_array($selectTeacher);
if(mysql_num_rows($selectTeacher)>=1){
    echo '
    <legend><strong>Información del docente</strong></legend><br>
    <input type="hidden" value="'.$dataTeacher["DUI"].'" name="teachingDUI" >
    <div class="group-material">
        <input type="text" class="material-control tooltips-general" value="'.$dataTeacher["Nombre"].'" name="teachingName" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}" required="" maxlength="50" data-toggle="tooltip" data-placement="top" title="Escribe los nombres del docente, solamente letras">
        <span class="highlight"></span>
        <span class="bar"></span>
        <label>Nombres</label>
    </div>
    <div class="group-material">
        <input type="text" class="material-control tooltips-general" value="'.$dataTeacher["Apellido"].'" name="teachingSurname" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}" required="" maxlength="50" data-toggle="tooltip" data-placement="top" title="Escribe los apellidos del docente, solamente letras">
        <span class="highlight"></span>
        <span class="bar"></span>
        <label>Apellidos</label>
    </div>
    <div class="group-material">
        <input type="text" class="material-control tooltips-general" value="'.$dataTeacher["Telefono"].'" name="teachingPhone" pattern="[0-9]{8,8}" required="" maxlength="8" data-toggle="tooltip" data-placement="top" title="Solamente 8 números">
        <span class="highlight"></span>
        <span class="bar"></span>
        <label>Teléfono</label>
    </div>
    <div class="group-material">
        <input type="text" class="material-control tooltips-general" value="'.$dataTeacher["Especialidad"].'" name="teachingSpecialty" required="" maxlength="40" data-toggle="tooltip" data-placement="top" title="Especialidad del docente">
        <span class="highlight"></span>
        <span class="bar"></span>
        <label>Especialidad</label>
    </div>
    <legend>Turno y Sección encargada</legend>
    <div class="group-material">
        <select class="material-control tooltips-general" name="teachingSection" data-toggle="tooltip" data-placement="top" title="Elige la sección encargada del docente">';
            $checkSectiont=ejecutarSQL::consultar("SELECT * FROM seccion WHERE CodigoSeccion='".$dataTeacher["CodigoSeccion"]."'");
            $dataSection=mysql_fetch_array($checkSectiont);
            echo '<option value="'.$dataSection['CodigoSeccion'].'">'.$dataSection['Nombre'].'</option>';
            $checkSection=ejecutarSQL::consultar("SELECT * FROM seccion WHERE CodigoSeccion <> '".$dataTeacher["CodigoSeccion"]."' ORDER BY Nombre ASC");
            while($fila=mysql_fetch_array($checkSection)){
                $checkSectionTeacher=ejecutarSQL::consultar("SELECT * FROM docente WHERE CodigoSeccion='".$fila['CodigoSeccion']."'");
                if(mysql_num_rows($checkSectionTeacher)<=0){
                   echo '<option value="'.$fila['CodigoSeccion'].'">'.$fila['Nombre'].'</option>'; 
                } 
                mysql_free_result($checkSectionTeacher);
            }
            mysql_free_result($checkSection);
            mysql_free_result($checkSectiont);
        echo '</select>
    </div>
    <div class="group-material">
        <select class="material-control tooltips-general" name="teachingTime" data-toggle="tooltip" data-placement="top" title="Elige el turno que labora el docente">';
            switch ($dataTeacher["Jornada"]){
                case 'Mañana':
                    echo'<option value="Mañana">Mañana</option><option value="Tarde">Tarde</option>';
                break;
                case 'Tarde':
                    echo'<option value="Tarde">Tarde</option><option value="Mañana">Mañana</option>';
                break;
                default :
                    echo'<option value="Mañana">Mañana</option><option value="Tarde">Tarde</option>';
                break;
            }
        echo '</select>
    </div>';
}else{
    echo '<div class="alert alert-danger text-center" role="alert"><strong><i class="zmdi zmdi-alert-triangle"></i> &nbsp; ¡Error!:</strong> Lo sentimos ha ocurrido un error.</div>';
}
mysql_free_result($selectTeacher);

