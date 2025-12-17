<?php
function ShowCSS()
{
    echo '
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>Shareflix - Plataforma de Streaming</title>
        <meta name="description" content="Shareflix - Tu plataforma de películas y series favoritas" />
        
        <link rel="stylesheet" href="../css/boxicons.css" />
        <link rel="stylesheet" href="../css/core.css" />
        <link rel="stylesheet" href="../css/shareflix.css" />
        <link rel="stylesheet" href="../css/demo.css" />
        <link rel="stylesheet" href="../css/perfect-scrollbar.css" />
        <link rel="stylesheet" href="../css/page-auth.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        
        <script src="../js/helpers.js"></script>
        <script src="../js/config.js"></script>
    </head>';
}

function ShowJS()
{
    echo '
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/perfect-scrollbar.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/main.js"></script>';
}

function MostrarMensaje()
{
    if(isset($_POST["Mensaje"]))
    {
        $tipoMensaje = isset($_POST["TipoMensaje"]) ? $_POST["TipoMensaje"] : "error";
        $claseAlerta = $tipoMensaje == "success" ? "alert-success" : "alert-error";
        
        echo '<div class="alert-shareflix ' . $claseAlerta . '" role="alert">' . $_POST["Mensaje"] . '</div>';
    }
}
?>
