<?php

namespace Core\Controller;

use Core\Extension\Twig\URIExtension;
use Core\Extension\Twig\AssetExtension;
use Core\Extension\Twig\FlashExtension;
use Core\Controller\Session\FlashService;
use Core\Extension\Twig\ContentExtension;

abstract class Controller
{

    private $twig;

    private $app;

/**
     * Méthode pour générer une nouvelle vue
     *
     * @return string
     */
    protected function render(string $view, array $variables = []): string
    {
        $variables["debugTime"] = $this->getApp()->getDebugTime();
        return $this->getTwig()->render(
            $view . '.html.twig',
            $variables
        );
    }

    /**
     * Méthodes liées à Twig, ajout de constantes, d'extensions
     *
     */
    private function getTwig()
    {
        if (is_null($this->twig)) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(dirname(__dir__)) . '/views/');
            $this->twig = new \Twig\Environment($loader);
            $this->twig->addGlobal('session', $_SESSION);
            $this->twig->addGlobal('constant', get_defined_constants());
            $this->twig->addExtension(new FlashExtension());
            $this->twig->addExtension(new URIExtension());
            $this->twig->addExtension(new ContentExtension());
            $this->twig->addExtension(new AssetExtension());

        }
        return $this->twig;
    }

    /**
     * Initie une instance App
     *
     */
    protected function getApp()
    {
        if (is_null($this->app)) {
            $this->app = \App\App::getInstance();
        }
        return $this->app;
    }

    /**
     * Génére une route grâce à son nom et ses paramètres (facultatif)
     *
     * @return string
     */
    protected function generateUrl(string $routeName, array $params = []): String
    {
        return $this->getApp()->getRouter()->url($routeName, $params);
    }

    /**
     * Récupère une table
     *
     * @return void
     */
    protected function loadModel(string $nameTable): void
    {
        $this->$nameTable = $this->getApp()->getTable($nameTable);
    }

    /**
     * Appelle la méthode pour les messages flash
     *
     * @return FlashService
     */
    protected function flash(): FlashService
    {
        return $this->getApp()->flash();
    }

    /**
     * Génère une url entière 
     *
     * @return string
     */
    protected function getUri(string $name, array $params = []): string
    {
        return  URLController::getUri($name, $params);
    }

    /**
     * Méthode plus rapide pour rediriger sur une autre page
     *
     * @return void
     */
    protected function redirect(string $path = '/')
    {
        header('location: '. $path);
        exit();
    }

    /**
     * Autorise uniquement les administrateurs à accèder à la page
     *
     * @return void
     */
    protected function onlyAdminAccess()
    {
        $this->loadModel('user');
        if (empty($_SESSION['auth']) || (int)$this->user->getUserDatasWithoutPwd($_SESSION['auth']->getId())->getRoleId() !== 777) {
            $this->redirect('/404');
        }
    }

    /**
     * Autorise uniquement les utilisateurs à accèder à la page
     *
     * @return void
     */
    protected function onlyUserAccess():void
    {
        if (!$_SESSION['auth']) {
            $this->redirect();
        }
    }

    /**
     * Autorise uniquement un utilisateur précis à accèder à la page
     *
     * @return void
     */
    protected function onlyUserAccessById($id):void
    {
        if ($_SESSION['auth']->getId() !== $id) {
            $this->redirect();
        }
    }

    /**
     * Autorise uniquement les visiteurs à accèder à la page
     *
     * @return void
     */
    protected function userForbidden():void
    {
        if ($_SESSION['auth']) {
            $this->redirect();
        }
    }

    /**
     * Méthode rapide afin de transformer une chaîne de caractère en slug
     */
    protected function slug(string $slug)
    {
        return (new Slugify())->slugify($slug);
    }

    /**
     * Vérifie les données d'un tableau avant ajout dans une base de données
     * 
     * @return array
     */
    protected function verifDatas(array $datas): array
    {
        foreach ($datas as $key => $data) {
            $verified[$key] = trim($data);
            $verified[$key] = htmlspecialchars($verified[$key]);
            $verified[$key] = stripslashes($verified[$key]);
        }
        return $verified;
    }
}
