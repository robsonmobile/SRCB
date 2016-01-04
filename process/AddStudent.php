<?php
session_start();
include '../library/configServer.php';
include '../library/consulSQL.php';
$studentNIE=consultasSQL::CleanStringText($_POST['studentNIE']);
$studentName=consultasSQL::CleanStringText($_POST['studentName']);
$studentSurname=consultasSQL::CleanStringText($_POST['studentSurname']);
$studentSection=consultasSQL::CleanStringText($_POST['studentSection']);
$representativeDUI=consultasSQL::CleanStringText($_POST['representativeDUI']);
$representativeName=consultasSQL::CleanStringText($_POST['representativeName']);
$representativeRelationship=consultasSQL::CleanStringText($_POST['representativeRelationship']);
$representativePhone=consultasSQL::CleanStringText($_POST['representativePhone']);
$responStatus=consultasSQL::CleanStringText($_POST['responStatus']);
if(!$studentSection==""){
    $checkRStudent=ejecutarSQL::consultar("SELECT * FROM estudiante WHERE Nombre='".$studentName."' AND Apellido='".$studentSurname."' AND CodigoSeccion='".$studentSection."'");
    if(mysql_num_rows($checkRStudent)<=0){
        $checkNIE=ejecutarSQL::consultar("SELECT * FROM estudiante WHERE NIE='".$studentNIE."'");
        if(mysql_num_rows($checkNIE)<=0){
            if($responStatus==1){
               if(consultasSQL::InsertSQL("estudiante", "NIE, DUI, CodigoSeccion, Nombre, Apellido, Parentesco", "'$studentNIE','$representativeDUI','$studentSection','$studentName','$studentSurname','$representativeRelationship'")){
                        echo '<script type="text/javascript">
                            swal({
                               title:"¡Estudiante registrado!",
                               text:"Los datos del estudiante se registraron exitosamente",
                               type: "success",
                               confirmButtonText: "Aceptar"
                            });
                            $(".form_SRCB")[0].reset();
                        </script>';
                }else{ 
                    echo '<script type="text/javascript">
                        swal({ 
                            title:"¡Ocurrió un error inesperado!", 
                            text:"No se pudo registrar el estudiante, por favor intenta nuevamente", 
                            type: "error", 
                            confirmButtonText: "Aceptar" 
                        });
                    </script>';
                } 
            }else{
                if(consultasSQL::InsertSQL("encargado", "DUI, Nombre, Telefono", "'$representativeDUI','$representativeName','$representativePhone'")){
                    if(consultasSQL::InsertSQL("estudiante", "NIE, DUI, CodigoSeccion, Nombre, Apellido, Parentesco", "'$studentNIE','$representativeDUI','$studentSection','$studentName','$studentSurname','$representativeRelationship'")){
                            echo '<script type="text/javascript">
                                swal({
                                   title:"¡Estudiante registrado!",
                                   text:"Los datos del estudiante se registraron exitosamente",
                                   type: "success",
                                   confirmButtonText: "Aceptar"
                                });
                                $(".form_SRCB")[0].reset();
                            </script>';
                    }else{
                       consultasSQL::DeleteSQL("encargado", "DUI='$representativeDUI'");
                        echo '<script type="text/javascript">
                            swal({ 
                                title:"¡Ocurrió un error inesperado!", 
                                text:"No se pudo registrar el estudiante, por favor intenta nuevamente", 
                                type: "error", 
                                confirmButtonText: "Aceptar" 
                            });
                        </script>';
                    }
                }else{
                    echo '<script type="text/javascript">
                        swal({ 
                            title:"¡Ocurrió un error inesperado!", 
                            text:"No se pudo registrar el estudiante, por favor intenta nuevamente", 
                            type: "error", 
                            confirmButtonText: "Aceptar" 
                        });
                    </script>';
                } 
            }
        }else{
            echo '<script type="text/javascript">
                swal({ 
                    title:"¡Ocurrió un error inesperado!", 
                    text:"El NIE ya está registrado. Dígita otro número de NIE, e intenta nuevamente", 
                    type: "error", 
                    confirmButtonText: "Aceptar" 
                });
            </script>';
        }
    }else{
        echo '<script type="text/javascript">
            swal({ 
                title:"¡Ocurrió un error inesperado!", 
                text:"Ya existe un estudiante registrado con el nombre y apellido que acabas de ingresar en la sección seleccionada", 
                type: "error", 
                confirmButtonText: "Aceptar" 
            });
        </script>';
    }   
}else{
    echo '<script type="text/javascript">
        swal({ 
            title:"¡Ocurrió un error inesperado!", 
            text:"No hay secciones disponibles. Debes registrar docentes y asignarles una sección encargada", 
            type: "error", 
            confirmButtonText: "Aceptar" 
        });
    </script>';
}
mysql_free_result($checkNIE);
mysql_free_result($checkRStudent);
