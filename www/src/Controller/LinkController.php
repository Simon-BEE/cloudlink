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
        if (!empty($_POST['title']) &&!empty($_POST['url']) && !empty($_POST['tag']) && !empty($_POST['description'])) {
            $datas = [];
            foreach ($_POST as $key => $value) {
                $datas[$key] = $value;
            }
            $verified = $this->verifDatas($datas);
            if ($this->link->create($verified)) {
                $objects = $this->link->lastLink();
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