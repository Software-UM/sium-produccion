/**
 * Created by osorio on 24/07/16.
 */
function validar(idEmpleado, idMateria, horarios, idgrupo, dia, hora, ciclo, idCarrera,salon,url) {
    return $.ajax({
        type: "POST",
        url: url,
        data: {
            dia: dia,
            idMateria: idMateria,
            idEmpleado: idEmpleado,
            horario: horarios,
            idGrupo: idgrupo,
            hora: hora,
            ciclo: ciclo,
            idCarrera: idCarrera,
            salon:salon
        },
        dataType: "json",
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            return data;
        }
    });
}

function validarPosgrado(idEmpleado, idMateria, horaEntrada, idgrupo, dia, horaSalida, ciclo, idCarrera) {
    return $.ajax({
        type: "POST",
        url: " url('modules/personal/horario/grupo/validarp')}}",
        data: {
            dia: dia,
            idMateria: idMateria,
            idEmpleado: idEmpleado,
            horaEntrada: horaEntrada,
            idGrupo: idgrupo,
            horaSalida: horaSalida,
            ciclo: ciclo,
            idCarrera: idCarrera,
        },
        dataType: "json",
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            return data;
        }
    });
}

function deleteA(idClase, idAsignacionHora, idHorario,url) {
    return $.ajax({
        type: "POST",
        url: url,
        data: {
            idClase: idClase,
            idAsignacionHora: idAsignacionHora,
            idHorario: idHorario,
        },
        dataType: "json",
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            return data;
        }
    });
}

function modificarA(idHAsignado, idAsignacionHora, idEmpleado,idSalon,url) {
    return $.ajax({
        type: "POST",
        url: url,
        data: {
            idHAsignado: idHAsignado,
            idAsignacionHora: idAsignacionHora,
            idEmpleado: idEmpleado,
            idSalon:idSalon,
        },
        dataType: "json",
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            return data;
        }
    });
}