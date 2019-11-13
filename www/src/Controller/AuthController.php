<?php
namespace App\Controller;

use Core\Controller\Controller;
use Core\Controller\FormController;
use Core\Controller\Helpers\MailController;

class AuthController extends Controller
{
    /**
     * Le constructeur insère les données de la table *** dans la variable $this->***
     */
    public function __construct()
    {
        $this->loadModel('user');
    }

    /**
     * Méthode pour connecter un utilisateur, ou afficher la page de connexion
     */
    public function signIn()
    {
        $this->userForbidden();

        $form = new FormController();
        $form->field('username', ["require"])
            ->field('password', ["require"]);
        $errors =  $form->hasErrors();
        
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            
            if (empty($errors)) {
                $verifiedDatas = $this->verifDatas($datas);
                $user = $this->user->verifyUser(strtolower($verifiedDatas["username"]), $verifiedDatas["password"]);
                if ($user) {
                    $this->flash()->addSuccess("Vous êtes bien connecté");
                    $_SESSION['auth'] = $user;
                    $this->redirect('/');
                } else {
                    $this->flash()->addAlert("Identifiants incorrect");
                }
            } else {
                $this->flash()->addAlert("Veillez à remplir le formulaire correctement");
            }
        }
        return $this->render('auth/login', []);
    }

    /**
     * Méthode pour enregistrer un utilisateur, ou afficher la page d'inscription
     */
    public function signUp()
    {
        $this->userForbidden();

        $form = new FormController();
        $form->field('nickname', ["require"])
            ->field('mail', ["require"])
            ->field('password', ["require", "verify", "length" => 6]);
        $errors =  $form->hasErrors();
        
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            
            if (empty($errors)) {
                $verifiedDatas = $this->verifDatas($datas);
                $verifiedDatas["mail"] = strtolower($verifiedDatas["mail"]);

                if ($this->user->find($verifiedDatas["mail"], "mail") || $this->user->find(strtolower($verifiedDatas["nickname"]), "nickname")) {
                    throw new \Exception("Les informations renseignées existent déjà dans nos fichiers");
                    exit();
                }
                $verifiedDatas["password"] = password_hash($verifiedDatas["password"], PASSWORD_BCRYPT);
                $verifiedDatas["token"] = substr(md5(uniqid()), 10, 20);
                
                if (!$this->user->create($verifiedDatas)) {
                    throw new \Exception("Une erreure technique est survenu, veuillez réessayer ultérieurement");
                    exit();
                }

                $this->flash()->addSuccess("Vous êtes bien enregistré");
                
                $this->redirect();
            } else {
                $this->flash()->addAlert("Veillez à remplir le formulaire correctement");
            }

            unset($verifiedDatas['password']);
        }

        return $this->render('auth/register', [
            'title' => 'S\'enregistrer',
            'datas' => $verifiedDatas
        ]);
    }

    /**
     * Méthode pour vérifier un utilisateur depuis un mail envoyé, et renvoie sur le formulaire de connexion
     */
    public function confrmAccount(string $token, int $id)
    {
        $this->userForbidden();
        
        if ($this->user->find($token, "token")) {
            if ($this->user->find($id)->getToken() === $token && $this->user->find($token, "token")->getId() === $id) {
                if ($this->user->updateToken($id, $token)) {
                    $this->flash()->addSuccess("Votre compte est bien vérifié, vous pouvez vous connecter");
                }else{
                    $this->flash()->addAlert("Votre compte a déjà était activé");
                }
            }else{
                $this->flash()->addAlert("Une erreur est survenu");
            }
        }else{
            $this->flash()->addAlert("Une erreur est survenu");
        }

        return $this->redirect('/login');
    }


    /**
     * Méthode pour générer et envoyer un mot de passe provisoire à un utilisateur qui l'aurait oublié, et affiche lson formulaire
     */
    public function newPassword()
    {
        $this->userForbidden();

        $form = new FormController();
        $form->field('mail', ["require"]);
        $errors =  $form->hasErrors();
        
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            
            if (empty($errors)) {
                $verifiedDatas = $this->verifDatas($datas);

                if (filter_var($verifiedDatas['mail'], FILTER_VALIDATE_EMAIL)) {

                    if ($this->user->find($verifiedDatas["mail"], "mail")) {
                        $newPass = $this->user->tempPassword($verifiedDatas['mail']);
                        $msg = ["html" => MailController::setMsgPassword($newPass, "Madame/Monsieur")];
                        MailController::sendMailToHim("Nouveau mot de passe provisoire", $verifiedDatas['mail'], $msg);
                        
                        $this->flash()->addSuccess("Un email avec un nouveau mot de passe provisoire vous a bien été envoyé");
                        $this->redirect('/login');
                    }
                }else{
                    $this->flash()->addAlert("Veillez à remplir le formulaire correctement");
                }
                
            } else {
                $this->flash()->addAlert("Veillez à remplir le formulaire correctement");
            }
        }

        return $this->render('auth/newpass', [
            'title' => 'Mot de passe oublié'
        ]);
    }

    /**
     * Méthode pour déconnecter un utilisateur, puis le redirige sur l'accueil
     */
    public function logOut()
    {
        session_destroy();
        return $this->redirect();
    }

}