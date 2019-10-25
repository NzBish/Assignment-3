<?php
namespace ktc\a2\controller;

use ktc\a2\view\View;

/**
 * Class HomeController
 *
 * @package ktc/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author  K. Dempsey
 * @author  T. Crompton
 * @author  C. Bishop
 */
class HomeController extends Controller
{

    /**
     * Home Index action
     *
     * Depending on login status, either:
     * - Constructs a welcome page from template
     * or:
     * - Redirects to UserController::loginAction
     *
     * @uses $_SESSION['userName'] to determine if a user is logged in
     */
    public function indexAction()
    {
        session_start();
        if (isset($_SESSION['userName'])) {
            $view = new View('welcome');
            echo $view->render();
        } else {
            $this->redirect('userLogin');
        }
    }
}
