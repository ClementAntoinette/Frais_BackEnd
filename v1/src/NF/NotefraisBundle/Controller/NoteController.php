<?php

// src/NF/NotefraisBundle/Controller/NoteController.php

namespace NF\NotefraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//CREATION FORMULAIRE
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{

    public function requeteoptionAction()
    { // SI METHODE OPTIONS
        return new Response('ok');
    }

    public function connect()
    {
        /* Connexion à une base ODBC avec l'invocation de pilote */
        $dsn = 'mysql:dbname=frais;host=127.0.0.1;charset=UTF8';
        $user = 'frais';
        $password = 'frais12';

        //        echo(dirname(__FILE__));

        try {
            $dbh = new \PDO($dsn, $user, $password);
            $rep = "reussite";
        } catch (PDOException $e) {
            //$rep = 'Connexion échouée : ' . $e->getMessage();
            $rep = "echec";
        }

        return $dbh;
    }

    public function indexAction()
    {
        return $this->redirect('http://etudiant12.chezmeme.com/rest/v1/');
    }

    public function check($token)
    {
        $module = 'notes';
        $tabrep = array();

        $url = 'http://chezmeme.com/sso/api/v1.6/check_access/' . $token . '/' . $module;
        $rep = file_get_contents($url);
        $json = json_decode($rep);

        $tabrep[] = $json;
        $rep = $tabrep[0]->ret;

        if ($rep === 'granted') {
            return $rep;
        } else {
            return $rep;
        }
    }

//    Utilisation du token avec l'API SSO
    public function logAction($login, $pwd)
    {
        $url = 'http://chezmeme.com/sso/api/v1.6/login/' . $login . '/' . $pwd;
        //$url = 'http://chezmeme.com/sso/api/v1.6/check_access/' . $token . '/' . $module;
        //$url2 = 'http://chezmeme.com/sso/api/v1.6/who_is/' . $token;

        $tabrep = array();
        $response = new Response();

        $rep = file_get_contents($url);
        $json = json_decode($rep);

        //$rep2 = file_get_contents($url2);
        //$json2 = json_decode($rep2);

        $tabrep[] = $json;
        //$tabrep[] = $json2;

        $response->setContent(json_encode($tabrep));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');

        return $response;

//        return new JsonResponse($tabrep);
    }

    /**************************      UTILISATEURS *************************************/
    /****  APRES L'APPARITION DE L'API SSO CES METOHDES NE SONT PLUS UTILISEES  *******/
    public function usersAction($token)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `usr_nom`, `usr_prenom`, `usr_genre`, `usr_mail` FROM nf_utilisateur_usr');
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC); //Utilisation du backslash pour ne pas etre dependant des chemins definis dans symfony

            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        } else {
            $result['ret'] = false;

            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
    }

    public function userAction($token, $id)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();
        
        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `usr_nom`, `usr_prenom`, `usr_genre`, `usr_mail` FROM nf_utilisateur_usr WHERE usr_id = ' . $id);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        } else {
            $result['ret'] = 'Token invalide';
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
            
//            return new JsonResponse($result); */
//            return new Response("Token invalide", Response::HTTP_OK);
        }
    }

    public function ajoutuserAction()
    {
        $nom = addslashes($_POST['nom']);
        $prenom = addslashes($_POST['prenom']);
        $genre = addslashes($_POST['genre']);
        $mail = addslashes($_POST['mail']);

        $req = 'INSERT INTO `nf_utilisateur_usr`(`usr_nom`, `usr_prenom`, `usr_genre`, `usr_mail`) VALUES ("' . $nom . '","' . $prenom . '","' . $genre . '","' . $mail . '")';
        $dbh = $this->connect();
        $sql = $dbh->prepare($req);
        $sql->execute();
        $id = $dbh->lastInsertId();
        $result = $sql->fetchAll();
        return new Response($id);
    }

    public function suppuserAction($id, Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');

        if ($request->isMethod("OPTIONS")) {

            $result['ret'] = 'option';

            $response->setContent(json_encode($result));
            return $response;
        }

        $req = 'DELETE FROM `nf_utilisateur_usr` WHERE `usr_id` = ' . $id;

        $dbh = $this->connect();
        $sql = $dbh->prepare($req);
        $sql->execute();

        $result['ret'] = true;
        $response->setContent(json_encode($result));

        return $response;

//        return new Response($req);
    }

    /***************************** NOTES   *******************************************/

    /*** PERMET D'AFFICHER TOUTES LES NOTES ******/

    public function notesAction($token)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `note_id`,`note_comment`, `note_verrou`, `note_usr_id`, `note_date` FROM nf_note');
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;

//            return new JsonResponse($result);
        } else {
            $result['ret'] = $check;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
            
//            return new JsonResponse($result);

        }
    }

    /*** PERMET D'AFFICHER UNE NOTE ******/

    public function noteAction($token, $id)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `note_id`,`note_comment`, `note_verrou`, `note_usr_id`, `note_date` FROM nf_note WHERE note_id = ' . $id);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) == 0) {
                $result = array('Pas de notes');
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            } else {
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            }
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
            
//            return new JsonResponse($result);
        }
    }

    /*** PERMET D'AJOUTER UNE NOTE ******/

    public function ajoutnoteAction($token)
    {
        $verrou = addslashes($_POST['verrou']);
        $comment = addslashes($_POST['comment']);
        $date = addslashes($_POST['date']);

        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {

            $tabrep = array();

            $url = 'http://chezmeme.com/sso/api/v1.6/who_is/' . $token;
            $rep = file_get_contents($url);
            $json = json_decode($rep);

            $tabrep[] = $json;

            if ($tabrep[0]->ret != 0) {
                $newusr = $tabrep[0]->id;
                $req = 'INSERT INTO `nf_note`(`note_comment`, `note_verrou`, `note_usr_id`, `note_date`) VALUES ("' . $comment . '","' . $verrou . '","' . $newusr . '","' . $date . '")';

                $dbh = $this->connect();
                $sql = $dbh->prepare($req);

                /*$id = $dbh->lastInsertId();
                $result = $sql->fetchAll();
                return new Response($url);*/

                $rep = array();

                if ($sql->execute()) {
                    $id = $dbh->lastInsertId();
                    $rep['result'] = $id;
                } else {
                    $rep['result'] = false;
                }
                $response->setContent(json_encode($rep));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;

//                return new JsonResponse($rep);
            } else {
                $result = array('Token inconnu');
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;

//                return new JsonResponse($result);
            }
        } else {
            $result['ret'] = $check;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }

    }

    /*** PERMET DE SUPPRIMER UNE NOTE ******/

    public function suppnoteAction($token, $id, Request $request)
    {
        $check = $this->check($token);
        $result = array();

        /*********        VA PREMETTRE DE NE PAS ACTIVER CorsE    ****************/

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');

        if ($request->isMethod("OPTIONS")) {

            $result['ret'] = 'option';

            $response->setContent(json_encode($result));
            return $response;
        }

        if ($check === 'granted') {
            $id = addslashes($id);
            $req = 'DELETE FROM `nf_note` WHERE `note_id` = ' . $id;

            $dbh = $this->connect();
            $sql = $dbh->prepare($req);

            $rep = array();

            if ($sql->execute()) {
                $rep['result'] = true;
            } else {
                $rep['result'] = false;
            }

            $response->setContent(json_encode($rep));
            return $response;
//                return new JsonResponse($rep);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            return $response;

//                return new JsonResponse($result);
        }
    }

    /*** PERMET DE MODIFIER UNE NOTE ******/

    public function updatenoteAction($token, $id, Request $request)
    {

        $id = addslashes($id);
        $verrou = addslashes($_GET['verrou']);
        $comment = addslashes($_GET['comment']);
        $date = addslashes($_GET['date']);

        $check = $this->check($token);
        $result = array();

        /*********        VA PREMETTRE DE NE PAS ACTIVER CorsE    ****************/

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');

        if ($request->isMethod("OPTIONS")) {

            $result['ret'] = 'option';

            $response->setContent(json_encode($result));
            return $response;
        }

        if ($check === 'granted') {
            $tabrep = array();

            $url = 'http://chezmeme.com/sso/api/v1.6/who_is/' . $token;
            $rep = file_get_contents($url);
            $json = json_decode($rep);

            $tabrep[] = $json;

            if ($tabrep[0]->ret != 0) {
                $usr = $tabrep[0]->id;
                $req = 'UPDATE `nf_note` SET `note_comment`= "' . $comment . '",`note_verrou`= "' . $verrou . '" ,`note_usr_id`= ' . $usr . ',`note_date`= "' . $date . '" WHERE note_id =' . $id;

                $dbh = $this->connect();
                $sql = $dbh->prepare($req);

                $rep = array();

                if ($sql->execute()) {
                    $rep['result'] = true;
                } else {
                    $rep['result'] = false;
                }
                $response->setContent(json_encode($rep));
                return $response;
//                    return new JsonResponse($rep);
            } else {
                $result = array('Token inconnu');
                $response->setContent(json_encode($result));
                return $response;
//                    return new JsonResponse($result);
            }
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            return $response;
//                return new JsonResponse($result);
        }
    }

    /***************************** FRAIS   ********************************************/

    /**      PERMET D'AFFICHER TOUS LES FRAIS      **/

    public function fraisAction($token)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `frais_id`,`frais_comment`,`frais_montant`,`frais_id_note`,`frais_id_type` FROM nf_frais');
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) == 0) {
                $result = array('Pas de frais');
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            } else {
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            }
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function unfraisAction($token, $id)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `frais_id`,`frais_comment`,`frais_montant`,`frais_id_note`,`frais_id_type` FROM nf_frais WHERE frais_id = ' . $id);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function ajoutfraisAction($token)
    {
        $commentFrais = addslashes($_POST['commentFrais']);
        $montantFrais = addslashes($_POST['montantFrais']);
        $idNote = addslashes($_POST['idNote']);
        $idType = addslashes($_POST['idType']);

        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $req = 'INSERT INTO `nf_frais`(`frais_comment`,`frais_montant`,`frais_id_note`,`frais_id_type`) VALUES ("' . $commentFrais . '",' . $montantFrais . ',' . $idNote . ',' . $idType . ')';

            $dbh = $this->connect();
            $sql = $dbh->prepare($req);
            if ($sql->execute()) {
                $id = $dbh->lastInsertId();
                $rep['result'] = $id;
            } else {
                $rep['result'] = false;
            }
            $response->setContent(json_encode($rep));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($rep);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function suppfraisAction($token, $id, Request $request)
    {
        $check = $this->check($token);
        $result = array();
        /*********        VA PREMETTRE DE NE PAS ACTIVER CorsE    ****************/

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');

        if ($request->isMethod("OPTIONS")) {

            $result['ret'] = 'option';
            $response->setContent(json_encode($result));
            return $response;
        }

        if ($check === 'granted') {
            $id = addslashes($id);
            $req = 'DELETE FROM nf_frais WHERE frais_id = ' . $id;

            $dbh = $this->connect();
            $sql = $dbh->prepare($req);

            $rep = array();

            if ($sql->execute()) {
                $rep['result'] = true;
            } else {
                $rep['result'] = false;
            }
            $response->setContent(json_encode($rep));
            return $response;
//                return new JsonResponse($rep);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            return $response;
//                return new JsonResponse($result);
        }

    }

    public function updatefraisAction($token, $id, Request $request)
    {
        $id = addslashes($id);
        $comment = addslashes($_GET['commentFrais']);
        $montant = addslashes($_GET['montantFrais']);
        $idnote = addslashes($_GET['idNote']);
        $idtype = addslashes($_GET['idType']);

        $check = $this->check($token);
        $result = array();
        /*********        VA PREMETTRE DE NE PAS ACTIVER CorsE    ****************/

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');

        if ($request->isMethod("OPTIONS")) {

            $result['ret'] = 'option';
            $response->setContent(json_encode($result));
            return $response;
        }

        if ($check === 'granted') {
            $req = 'UPDATE `nf_frais` SET `frais_comment`= "' . $comment . '",`frais_montant`= ' . $montant . ',`frais_id_note`= ' . $idnote . ',`frais_id_type`= ' . $idtype . ' WHERE `frais_id`=' . $id;

            $dbh = $this->connect();
            $sql = $dbh->prepare($req);

            $rep = array();

            if ($sql->execute()) {
                $rep['result'] = true;
            } else {
                $rep['result'] = false;
            }
            $response->setContent(json_encode($rep));
            return $response;

//                return new JsonResponse($rep);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            return $response;

//                return new JsonResponse($result);
        }
    }

    /***************************** TYPES   *******************************************/

    public function typesAction($token)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `type_id`,`type_nom` FROM nf_type');
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function typeAction($token, $id)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT `type_id`,`type_nom` FROM nf_type WHERE type_id = ' . $id);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function ajouttypeAction($token)
    {
        $type = addslashes($_POST['type']);
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {

            $req = 'INSERT INTO `nf_type`(`type_nom`) VALUES ("' . $type . '")';
            $dbh = $this->connect();
            $sql = $dbh->prepare($req);
            $sql->execute();
            $id = $dbh->lastInsertId();
            $result['ret'] = $id;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new Response($id);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function deletetypeAction($token, $id, Request $request)
    {
        $check = $this->check($token);
        $result = array();

        /*********        VA PREMETTRE DE NE PAS ACTIVER CorsE    ****************/

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');

        if ($request->isMethod("OPTIONS")) {

            $result['ret'] = 'option';
            $response->setContent(json_encode($result));
            return $response;
        }

        if ($check === 'granted') {
            $id = addslashes($id);
            $req = 'DELETE FROM `nf_type` WHERE `type_id` = ' . $id;

            $dbh = $this->connect();
            $sql = $dbh->prepare($req);

            $rep = array();

            if ($sql->execute()) {
                $rep['result'] = true;
            } else {
                $rep['result'] = false;
            }
            $response->setContent(json_encode($rep));
            return $response;

//                return new JsonResponse($rep);
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            return $response;

//                return new JsonResponse($result);
        }
    }

    /*****************   FONCTIONS COMPLEMENTAIRES **********************************/
    public function usernotesAction($token, $id)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $tabrep = array();

            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT * FROM nf_note WHERE note_usr_id = ' . $id);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) == 0) {
                $result = array('pas de notes');
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            } else {
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            }

        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

    public function notefraisAction($token, $id)
    {
        $check = $this->check($token);
        $result = array();
        $response = new Response();

        if ($check === 'granted') {
            $dbh = $this->connect();
            $sql = $dbh->prepare('SELECT * FROM `nf_frais`
                        JOIN nf_note ON nf_note.note_id = nf_frais.frais_id_note
                        WHERE  nf_note.note_id =' . $id);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) == 0) {
                $result = array('Pas de frais');
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            } else {
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
//                return new JsonResponse($result);
            }
        } else {
            $result['ret'] = false;
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
//            return new JsonResponse($result);
        }
    }

}
