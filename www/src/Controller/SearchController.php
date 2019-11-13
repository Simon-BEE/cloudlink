<?php
namespace App\Controller;

use Core\Controller\Controller;
use Core\Controller\FormController;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->loadModel('link');
    }

    /**
     * Searching tool
     */
    public function research()
    {
        $links = $this->link->all();
        $request = $_GET['search'];

        if (strlen($request) > 2) {
            $result = $this->link->findLinkByWord($request, $_SESSION['auth']->getId());
            if ($result) {
                $array = [];
                foreach ($result as $value) {
                    $array[] = $value->objectToArray($value);
                }
                $response = json_encode($array);
            }else{
                $response = 'error';
            }
        }

        echo $response;
    }
}