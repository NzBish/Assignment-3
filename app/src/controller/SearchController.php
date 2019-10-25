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
     * Search action
     *
     * Depending on login status, either:
     * - Builds and displays a search template
     * or:
     * - Redirects to UserController::loginAction
     *
     * @uses $_SESSION['userName'] to determine if a user is logged in
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
     * Search Retrieve action
     *
     * Used via AJAX to load products matching the search request from the database
     * If there are results, builds and echoes back a searchResults template containing the resulting ProductModels
     * If there are no results (or some other error occurs), echoes back an error instead
     *
     * @uses $_POST['search'] to construct a SearchCollectionModel to generate ProductModels for the searchResults template
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
