<?php
session_start();
include '../library/configServer.php';
include '../library/consulSQL.php';
$teachingDUI=consultasSQL::CleanStringText($_POST['teachingDUI']);
$teachingName=consultasSQL::CleanStringText($_POST['teachingName']);
$teachingSurname=consultasSQL::CleanStringText($_POST['teachingSurname']);
$teachingPhone=consultasSQL::CleanStringText($_POST['teachingPhone']);
$teachingSpecialty=consultasSQL::CleanStringText($_POST['teachingSpecialty']);
$teachingSection=consultasSQL::CleanStringText($_POST['teachingSection']);
$teachingTime=consultasSQL::CleanStringText($_POST['teachingTime']);
if(!$teachingSection==""){
    $checkDUI=ejecutarSQL::consultar("SELECT * FROM docente WHERE DUI='".$teachingDUI."'");
    if(mysql_num_rows($checkDUI)<=0){
        if(consultasSQL::InsertSQL("docente", "DUI, CodigoSeccion, Nombre, Apellido, Telefono, Especialidad, Jornada", "'$teachingDUI','$teachingSection','$teachingName','$teachingSurname','$teachingPhone','$teachingSpecialty','$teachingTime'")){ 
            echo '<script type="text/javascript">
                swal({
                   title:"¡Docente registrado!",
                   text:"Los datos del docente se almacenaron exitosamente",
                   type: "success",
                   confirmButtonText: "Aceptar"
                });
                $(".form_SRCB")[0].reset();
            </script>';
        }else{
            echo '<script type="text/javascript">
                swal({ 
                    title:"¡Ocurrió un error inesperado!", 
                    text:"No se pudo registrar el docente, por favor intenta nuevamente", 
                    type: "error", 
                    confirmButtonText: "Aceptar" 
                });
            </script>'; 
        }
    }else{
        echo '<script type="text/javascript">
            swal({ 
                title:"¡Ocurrió un error inesperado!", 
                text:"Este número de DUI está asociado a un docente registrado en el sistema, verifícalo e intenta nuevamente", 
                type: "error", 
                confirmButtonText: "Aceptar" 
            });
        </script>'; 
    }
}else{
    echo '<script type="text/javascript">
        swal({ 
            title:"¡Ocurrió un error inesperado!", 
            text:"No has seleccionado una sección válida o no has registrado secciones, verifica e intenta nuevamente", 
            type: "error", 
            confirmButtonText: "Aceptar" 
        });
    </script>'; 
}
mysql_free_result($checkDUI);