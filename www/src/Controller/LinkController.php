<?php
namespace App\Controller;

use Core\Controller\Controller;
use Core\Controller\FormController;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->loadModel('link');
    }

    /**
     * Searching tool
     */
    public function add()
    {
        $this->isPostMethod();

        if (!empty($_SESSION['auth'])) {
            if (!empty($_POST['title']) &&!empty($_POST['url']) && !empty($_POST['tag']) && !empty($_POST['description'])) {
                $datas = [];
                foreach ($_POST as $key => $value) {
                    $datas[$key] = $value;
                }
                $verified = $this->verifDatas($datas);
                $verified['user'] = $_SESSION['auth']->getId();
                if ($this->link->create($verified)) {
                    $objects = $this->link->lastLink($_SESSION['auth']->getId());
                    $news = [];
                    foreach ($objects as $value) {
                        $news[] = $value->objectToArray($value);
                    }
                    $response = json_encode($news);
                }else{
                    $response = 'error';
                }
                echo $response;
            }else{
                echo 'Please fill in the form correctly !';
            }
        }
    }

    public function delete()
    {
        $this->isPostMethod();
        
        $response = '';
        if (!empty($_SESSION['auth'] && !empty($_POST['user_id']) && !empty($_POST['id']))) {
            if ($_POST['user_id'] === $_SESSION['auth']->getId()) {
                if ($this->link->delete($_POST['id'])) {
                    $objects = $this->link->find($_SESSION['auth']->getId(), 'user', false);
                    $news = [];
                    foreach ($objects as $value) {
                        $news[] = $value->objectToArray($value);
                    }
                    $response = json_encode($news);
                }
            }
        }
        if ($response === '') {
            $response = 'erreur';
        }
        echo $response;
    }
}