<?php
session_start();
include '../library/configServer.php';
include '../library/consulSQL.php';
$primaryKey=consultasSQL::CleanStringText($_POST['primaryKey']);
$selectTea=ejecutarSQL::consultar("SELECT * FROM docente WHERE DUI='".$primaryKey."'");
$dataTeacher=mysql_fetch_array($selectTea);
$selectStudents=ejecutarSQL::consultar("SELECT * FROM estudiante WHERE CodigoSeccion='".$dataTeacher['CodigoSeccion']."'");
if(mysql_num_rows($selectStudents)<=0){
    $selectAllLoansT=ejecutarSQL::consultar("SELECT * FROM prestamodocente WHERE DUI='".$primaryKey."'");
    $totalP=0;
    while($rowA=mysql_fetch_array($selectAllLoansT)){
        $seletLP=ejecutarSQL::consultar("SELECT * FROM prestamo WHERE CodigoPrestamo='".$rowA['CodigoPrestamo']."' AND Estado='Prestamo'");
        if(mysql_num_rows($seletLP)>0){ $totalP=$totalP+1; }
        mysql_free_result($seletLP);
    }
    if($totalP<=0){
        $totalErrors=0;
        $selectAllLoansTe=ejecutarSQL::consultar("SELECT * FROM prestamodocente WHERE DUI='".$primaryKey."'");
        while($dataAllLoans=mysql_fetch_array($selectAllLoansTe)){
            if(consultasSQL::DeleteSQL("prestamodocente", "CodigoPrestamo='".$dataAllLoans['CodigoPrestamo']."'")){
                if(consultasSQL::DeleteSQL("prestamo", "CodigoPrestamo='".$dataAllLoans['CodigoPrestamo']."'")){
                    
                }else{
                    $totalErrors=$totalErrors+1;
                }
            }else{
                $totalErrors=$totalErrors+1; 
            }   
        }
        if($totalErrors<=0){
            if(consultasSQL::DeleteSQL("bitacora", "CodigoUsuario='".$primaryKey."' AND Tipo='Docente'")){
                if(consultasSQL::DeleteSQL("docente", "DUI='".$primaryKey."'")){
                    echo '<script type="text/javascript">
                        swal({ 
                            title:"¡Docente eliminado!", 
                            text:"Todos los datos del docente y préstamos asociados han sido eliminados del sistema exitosamente", 
                            type: "success", 
                            confirmButtonText: "Aceptar" 
                        },
                        function(isConfirm){  
                            if (isConfirm) {     
                               location.reload();
                            } else {    
                               location.reload();
                            } 
                        });
                    </script>';
                }else{
                    echo '<script type="text/javascript">
                        swal({ 
                            title:"¡Ocurrió un error inesperado!", 
                            text:"No hemos podido eliminar los datos del docente, por favor intenta nuevamente", 
                            type: "error", 
                            confirmButtonText: "Aceptar" 
                        });
                    </script>';  
                }
            }else{
                echo '<script type="text/javascript">
                    swal({ 
                        title:"¡Ocurrió un error inesperado!", 
                        text:"No hemos podido eliminar los datos del docente, por favor intenta nuevamente", 
                        type: "error", 
                        confirmButtonText: "Aceptar" 
                    });
                </script>';
            }
        }else{
           echo '<script type="text/javascript">
                swal({ 
                    title:"¡Ocurrió un error inesperado!", 
                    text:"No hemos podido eliminar los datos del docente, por favor intenta nuevamente", 
                    type: "error", 
                    confirmButtonText: "Aceptar" 
                });
            </script>'; 
        }
    }else{
        echo '<script type="text/javascript">
            swal({ 
                title:"¡Ocurrió un error inesperado!", 
                text:"El docente tiene préstamos pendientes, no se pueden borrar los datos del docente mientras no devuelva los libros", 
                type: "error", 
                confirmButtonText: "Aceptar" 
            });
        </script>';
    }
}else{
    echo '<script type="text/javascript">
        swal({ 
            title:"¡Ocurrió un error inesperado!", 
            text:"No se pueden eliminar los datos del docente porque en la sección encargada existen estudiantes registrados, no debe de haber ningún estudiante en dicha sección para eliminar los datos", 
            type: "error", 
            confirmButtonText: "Aceptar" 
        });
    </script>';
}
mysql_free_result($selectAllLoansT);
mysql_free_result($selectAllLoansTe);
mysql_free_result($selectTea);
mysql_free_result($selectStudents);