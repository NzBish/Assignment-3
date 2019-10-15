<?php
namespace ktc\a2\controller;

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

    public function indexAction()
    {
        session_start();
        if (isset($_SESSION['userName'])) {
            $this->redirect('productIndex');
        } else {
            $this->redirect('userLogin');
        }
    }
}
