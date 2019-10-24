<?php
namespace ktc\a2\controller;

use ktc\a2\Exception\StoreException;
use ktc\a2\model\SearchCollectionModel;
use ktc\a2\view\View;

/**
 * Class SearchController
 * @package ktc\a2\controller
 */
class SearchController extends Controller
{


    /**
     *Search for a product
     */
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


    /**
     *Retrieve a product
     */
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
            echo $ex->getMessage();
        }
    }
}
