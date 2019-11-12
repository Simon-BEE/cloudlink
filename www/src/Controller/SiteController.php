<?php
namespace App\Controller;

use Core\Controller\Controller;
use Core\Controller\FormController;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->loadModel('user');
        $this->loadModel('link');
    }

    /**
     * La page d'accueil du site
     */
    public function index()
    {
        return $this->render('site/index', [
            'title' => '',
            'lastLinks' => $this->link->lastLink()
        ]);
    }

    /**
     * La page d'accueil du site
     */
    public function all()
    {
        return $this->render('site/all', [
            'title' => '',
            'links' => $this->link->all()
        ]);
    }
    
    /**
     * Affichage d'une page 404 personnalisée
     */
    public function notFound()
    {
        return $this->render('site/404', [
            'title' => 'Erreur 404 - Page introuvable'
        ]);
    }
        // private function authControl(){
        //     $form = new FormController();
        //         $form->field('username', ["require"])
        //             ->field('password', ["require"]);
        //         $errors =  $form->hasErrors();
                
        //         if (!isset($errors["post"])) {
        //             $datas = $form->getDatas();
                    
        //             if (empty($errors)) {
        //                 $verifiedDatas = $this->verifDatas($datas);
        //                 $user = $this->user->verifyUser($verifiedDatas["username"], $verifiedDatas["password"]);
        //                 if ($user) {
        //                     if ($user->getToken() === 'c43!cked') {
        //                         $this->flash()->addSuccess("Vous êtes bien connecté");
        //                         $_SESSION['auth'] = $user;
        //                         //$this->redirect('/profile');
        //                     }else{
        //                         $this->flash()->addAlert("Veuillez confirmer votre compte avant de vous connecter");
        //                     }
        //                 } else {
        //                     $this->flash()->addAlert("L'adresse email et/ou le mot de passe est/son incorrect/s");
        //                 }
        //             } else {
        //                 $this->flash()->addAlert("Veillez à remplir le formulaire correctement");
        //             }
        //         }
        // }
    }