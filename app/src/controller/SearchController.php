<?php
namespace ktc\a2\controller;

use ktc\a2\Exception\StoreException;
use ktc\a2\model\SearchCollectionModel;
use ktc\a2\view\View;

class SearchController extends Controller
{
    public function searchAction()
    {
        session_start();

        if (isset($_SESSION['userName'])) {
            $view = new View('search');
            echo $view->render();
        } else {
            $this->redirect('userLogin');
        }
    }

    public function retrieveAction()
    {
        if (!isset($_POST['search'])) {
            $this->redirect('welcome');
        }
        try {
            $collection = new SearchCollectionModel($_POST['search']);
            $products = $collection->getResults();
            $view = new View('searchResults');
            echo $view->addData("products", $products)->render();
        } catch (StoreException $ex) {
            $view = new View('exception');
            echo $view->addData("exception", $ex)->addData("back", "Home")->render();
        }
    }
}


