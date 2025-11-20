function ConsultarNombre()
{
    let identificacion = document.getElementById("Identificacion").value;
    document.getElementById("Nombre").value = "";

    if(identificacion.length >= 9)
    {
        $.ajax({
            type: 'GET',
            url: 'https://apis.gometa.org/cedulas/' + identificacion,
            dataType: 'json',
            success: function(data){
                if(data.resultcount > 0)
                {
                    document.getElementById("Nombre").value = data.nombre;
                }
            }
        });
    }    
}

$(function () {

    $("#formPerfil").validate({
        rules: {
            Identificacion: {
                required: true
            },
            Nombre: {
                required: true
            },
            CorreoElectronico: {
                required: true
            },
            NombrePerfil: {
                required: true
            },
        },
        messages: {
            Identificacion: {
                required: "* Requerido"
            },
            Nombre: {
                required: "* Requerido"
            },
            CorreoElectronico: {
                required: "* Requerido"
            },
            NombrePerfil: {
                required: "* Requerido"
            }
        }
    });

});