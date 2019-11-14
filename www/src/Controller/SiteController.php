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
        $this->onlyUserAccess('/login');
        
        return $this->render('site/index', [
            'title' => '',
            'lastLinks' => $this->link->lastLink($_SESSION['auth']->getId())
        ]);
    }

    /**
     * La page d'accueil du site
     */
    public function all()
    {
        $this->onlyUserAccess('/login');

        if (!$allLinks = $this->link->find($_SESSION['auth']->getId(), 'user', false)) {
            $this->redirect();
        }

        return $this->render('site/all', [
            'title' => '',
            'links' => $allLinks
        ]);
    }
    
    /**
     * Affichage d'une page 404 personnalisée
     */
    public function notFound()
    {
        return $this->render('site/404', [
            'title' => '- Erreur 404 - Page introuvable'
        ]);
    }
}