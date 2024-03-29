<?php
/* Displays user information and some useful messages */
session_start();
include('connect.php');
// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: login-system/error.php");    
}
else {
    // Makes it easier to read
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $id_user = $_SESSION['id_user'];

    $req = "SELECT DISTINCT id_post, id_user, prenom, nom, lieu, humeur, date_post, document, avatar, descriptif FROM post
NATURAL JOIN users
INNER JOIN relation ON post.id_user = relation.id_user1 OR post.id_user = relation.id_user2 
WHERE relation.id_user1 = '$id_user' OR relation.id_user2 = '$id_user' 
ORDER BY post.date_post DESC";
    $resultat = mysqli_query($con, $req);
}
//Requete speciale pour recuperer avatar et background du user logged
$av = mysqli_query($con,"SELECT * FROM users WHERE id_user='$id_user'");
$user_obj = $av->fetch_assoc();
$dist_av=$user_obj['avatar'];
$dist_back=$user_obj['background'];
$dist_admin=$user_obj['admin'];
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Bienvenue sur LinkECE</title>
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

            #accueil{
                text-align: left;
            }


            #post-lieu{
                text-align: left;
                color: grey;
                   
            }

            #post-description{
                text-align: justify;
            }

            textarea
            {
                resize: none;
            }

            #post-humeur{
                color: grey;
                text-align: left;
                font-weight: bold;
            }
            #post-ami{
                color: black;
                text-align: left;
                font-weight: bold;
            }
            h2{
                text-align: left;
            }
            #date-comment{
                 text-align: left;
                color: grey;
                font-weight: normal;
                margin-left: 25px;
            }

        </style>
    </head>
    <body background="<?= $dist_back ?>">

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
                        <li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span> Accueil</a></li>
                        <li><a href="reseau.php"><span class="glyphicon glyphicon-globe"></span> Réseau</a></li>
                        <li><a href="chat/message.php"><span class="glyphicon glyphicon-envelope"></span> Messagerie</a></li>
                        <li><a href="emplois.php"><span class="glyphicon glyphicon-search"></span> Emplois</a></li>
                        <li><a href="notifications.php"><span class="glyphicon glyphicon-bell"></span> Notifications</a></li>
                        <?php if($dist_admin==1){echo "<li><a href='admin.php'><span class='glyphicon glyphicon-eye-open'></span> Page Admin</a></li>";}?>
                    </ul>


                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="profil.php"><span class="glyphicon glyphicon-user"></span> <?= $prenom.' '.$nom ?> </a></li>
                        <li><a href="login-system/logout.php"><span class="glyphicon glyphicon-log-out"></span> Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container text-center">    
            <h3 class="well">Bienvenue <?=$prenom?> !</h3>
            <div class="row">
                
                
                <div class="col-sm-9">
                    
                    <div class="well" id="accueil">
                        <form method="post" action="statut.php">
                            <textarea rows="4" cols="50" name="statut" class="form-control" placeholder="Des nouveautés à partager ?" required></textarea><br>
                            <input type="text" row="3" class="form-control" name="lieu" placeholder="Où êtes-vous ?" required></p>
                        <div class="row">
                            <div class="col-sm-5">
                                <p><br>
                                    <label for="humeur">Quelle est votre humeur ?</label>
                                    <select name="humeur" id="humeur">
                                        <option value="---">---</option>
                                        <option value="Heureux">Heureux</option>
                                        <option value="Cool">Cool</option>
                                        <option value="Dubitatif">Dubitatif</option>
                                        <option value="Triste">Triste</option>
                                        <option value="Enerve">Enervé</option>
                                    </select>
                                </p>
                            </div>
                            <div class="col-sm-5"><br>
                                <p><input name="monFichier" type="file"></p>
                            </div>
                            <div class="col-sm-2">
                                <br>
                                <button type="submit" class="btn btn-primary">

                                    <span class="glyphicon glyphicon-bullhorn"></span> Poster
                                </button>
                            </div>
                        </div>
                        </form>

                </div>
                <br>
                <h2 class="well">Fil d'Actualité</h2>
                <?php
    while($post = mysqli_fetch_array($resultat)){
        $blindage=0;
        $test = mysqli_query($con,"SELECT * FROM commentaire WHERE id_post=".$post['id_post']."");
        $test_obj = $test->fetch_assoc();
        $dist_test=$test_obj['id_post'];
        if($post['id_post']==$test_obj['id_post']){
            $blindage=1;
        }
        
        $id_post = $post['id_post'];
        
        $req_like = mysqli_query($con,"SELECT COUNT(*) as nb FROM `like` WHERE `like`.id_post='$id_post'");
        $nb = $req_like->fetch_assoc();
        $req_like = mysqli_query($con,"SELECT COUNT(*) as nb FROM `like` WHERE `like`.id_post='$id_post' AND `like`.id_user='$id_user'");
        $bool = $req_like->fetch_assoc();

        $time = strtotime($post['date_post']);
        $myFormatForView = date("d/m/y à H:i", $time);
        $txt = "Posté par ".$post['prenom']. " ".$post['nom']." depuis ".$post['lieu']." le ".$myFormatForView." : ".$post['descriptif'];
        echo "<div class='row'>
                    
                        <div class='col-sm-2'>
                            <div class='well'>
                                <a href='viewprofile.php?id_user=".$post['id_user']."'>".$post['prenom']." ".$post['nom']."</a>
                                <img src=".$post['avatar']." class='img-circle' height='55' width='55' alt='Avatar'>
                            </div>
                        </div>

                        <div class='col-sm-10'>


                            <div class='well'>";





        if($post['humeur'] != "---"){
            echo "<p id='post-humeur'>".$post['humeur']."</p>";
        }
        echo "<p id='post-description'>".$post['descriptif']."</p>";

        if($post['document']){
            echo"<img src=".'img/'.$post['document']." width='400px' ><p><br></p>";
        }


        echo "
                                <div class='row'>
                                <div class='col-sm-6'>
                                <p id='post-lieu'> Posté depuis ".$post['lieu']." le ".$myFormatForView."</p>";?>

                                
                                <a href="mailto: ?subject=LinkECE - Post à voir&body=<?=$txt?>"><button type='button' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-share'></span> Partager</button></a><p></p><?php
                                
                                if($bool['nb']>0){
                                    echo "<a href='dislike.php?id_post=". $post['id_post'] ."&id_url=accueil.php'><button class='btn btn-default btn-sm'><span class='glyphicon glyphicon-thumbs-up'></span> Déjà aimé (".$nb['nb'].")</button></a>";
                                }else{
                                    echo "<a href='like.php?id_post=".$post['id_post']."&id_url=accueil.php'><button type='button' class='btn btn-success btn-sm'><span class='glyphicon glyphicon-thumbs-up'></span> J'aime (".$nb['nb'].")</button></a>";
                                }
        
                                echo "
                                </div>
                                <div class='col-sm-6 '>
                                <p> <a href='viewpost.php?id_post=".$post['id_post']."'> Voir la publication </a></p>

                                <form class='form' action='commentaire.php?id_post=".$post['id_post']."' method='post' autocomplete='off'>
                                   
                                <input type='text' class='form-control' name='commentaire' placeholder='Écrire un commentaire...' required><p></p>
                                   
                                <button type='submit' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-comment'></span> Commenter</button></form>
                                
                                </div>
                                </div>
                            </div>";
                                        if($blindage==1 ){
                                    $req3 = "SELECT commentaire.id_commentaire, commentaire.id_user, commentaire.id_post, commentaire.commenatire, commentaire.date_commentaire, users.id_user, users.prenom, users.nom FROM commentaire, users WHERE commentaire.id_user = users.id_user AND commentaire.id_post=".$dist_test." ORDER BY commentaire.date_commentaire DESC LIMIT 4";
    $resultat3 = mysqli_query($con, $req3);
                                    while($sku = mysqli_fetch_array($resultat3)){
                                                           $ish = strtotime($sku['date_commentaire']);
        $myFormatForView = date("d/m/y à H:i", $ish);
                                    echo " 
                                    <div class='row'>
                                        <div class='col-sm-3'></div>
                                            <div class='col-sm-9'>
                                                <div class='well'>                                    
                                                    <p id='post-ami'> Commentaire de ".$sku['prenom'].' '.$sku['nom']."<span id='date-comment'>(".$myFormatForView.")<span></p>
                                                    <p id='post-description'> ".$sku['commenatire']."</p>
                                                </div>
                                            </div>
                                        </div>
                                    ";}
                                }
                
                                echo "
                        </div>  
                    </div>";
    }?>    
            </div>

            <div class="col-sm-3">
                <div class="well">
                    <p><a href="profil.php">Mon Profil</a></p>
                    <img src="<?= $dist_av ?>" class="img-circle" height="55" width="55" alt="Avatar">
                </div>

                <div class="well">
                    <a href="elargir.php" class="btn btn-success"><span class="glyphicon glyphicon-briefcase"></span> Élargir son réseau</a><p> </p>
                    <a href="elargiremploi.php" class="btn btn-info"><span class="glyphicon glyphicon-pushpin"></span> Trouver un emploi</a>
                </div>

                <div class="well">
                    <p>Évènements à venir :</p>
                    <p><strong>Paris</strong></p>
                    <p>Vendredi 27 Novembre 2018</p>
                </div>



            </div>
        </div>
        </div>

  
    </body>
</html>

