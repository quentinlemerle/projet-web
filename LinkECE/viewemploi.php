<?php
/* Displays user information and some useful messages */
session_start();
include('connect.php');

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "Vous devez vous connecter!";
    header("location: login-system/error.php");    
}

else{

    //ID de l'utilisateur
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $id_user = $_SESSION['id_user'];

    if(isset($_GET['id_emploi']))

    {
        //Id de l'utilisateur dont on regarde le profil
        $idviewed = $_GET['id_emploi'];

        //Infos sur l'utilisateur dont on regarde le profil
        $emploi = mysqli_query($con, "SELECT * FROM `emploi` WHERE id_emploi='$idviewed'");

                //Assoc de l'user viewed
                $emploi_viewed = mysqli_fetch_assoc($emploi);
                //On récupère tous ses paramètres
                $emploi_viewed_id= $emploi_viewed['id_emploi'];
                $emploi_viewed_date= $emploi_viewed['date_emploi'];
                $emploi_viewed_entreprise= $emploi_viewed['entreprise'];
                $emploi_viewed_type_offre= $emploi_viewed['type_offre'];
                $emploi_viewed_id= $emploi_viewed['id_emploi'];
                $emploi_viewed_descriptif= $emploi_viewed['descriptif_emploi'];
                $emploi_viewed_intitule= $emploi_viewed['intitule_offre'];
                $emploi_viewed_disponibilite= $emploi_viewed['disponibilite'];

                //Si l'offre n'est plus disponible
                if($emploi_viewed_disponibilite==0){
                    echo 'Cette offre a expiré';
                    die;
                }
    }
}
?>


<!DOCTYPE html>
<html>

    <head>
        <title>Offre d'emploi de <?php echo $emploi_viewed_entreprise; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>    
            /* Set black background color, white text and some padding */
            footer {
                background-color: #555;
                color: white;
                padding: 15px;
            }

            .infos{
                text-align: left;
            }

            h3{
                text-align: left;
            }

        </style>
    </head>
    <body>

        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <style>.navbar-brand {font-weight: bold;}</style>
                    <a class="navbar-brand" href="accueil.php">LinkECE</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li><a href="accueil.php"><span class="glyphicon glyphicon-home"></span> Accueil</a></li>
                        <li><a href="reseau.php"><span class="glyphicon glyphicon-globe"></span> Réseau</a></li>
                        <li><a href="chat/message.php"><span class="glyphicon glyphicon-envelope"></span> Messagerie</a></li>
                        <li><a href="emplois.php"><span class="glyphicon glyphicon-search"></span> Emplois</a></li>
                        <li><a href="notifications.php"><span class="glyphicon glyphicon-bell"></span> Notifications</a></li>
                    </ul>


                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="profil.php"><span class="glyphicon glyphicon-user"></span> <?= $prenom.' '.$nom ?> </a></li>
                        <li><a href="login-system/logout.php"><span class="glyphicon glyphicon-log-out"></span> Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container text-center">    
            <div class="row">
                
                <div class="col-sm-12">
                    
                        
                            <h3 class="well"> Informations</h3>
                            <div class="well">
                            <div class="infos">
                                
                                <p>  Date de mise en ligne : <?= $emploi_viewed_date ?></p>
                                <p>  Entreprise : <?= $emploi_viewed_entreprise ?></p>
                                <p>  Intitulé de l'offre : <?= $emploi_viewed_intitule ?></p>
                                <p>  Type d'offre : <?= $emploi_viewed_type_offre ?></p>
                                <p>  Descriptif de l'offre : <br/><br/> <?= $emploi_viewed_descriptif ?></p>
                                <p style="text-align: center;">  <a href="mailto:<?= 'recrutement@gmail.com' ?>">Postuler à cette offre</a></p>
                                
                            </div>
                            </div>

                       
                     
                </div>
            </div>
        </div>

        <footer class="container-fluid text-center">
            <p>LinkECE &copy;2018</p>
        </footer>

    </body>
</html>