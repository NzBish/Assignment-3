<?php
namespace ktc\a2\controller;

use ktc\a2\Exception\StoreException;
use ktc\a2\model\UserModel;
use ktc\a2\model\UserCollectionModel;
use ktc\a2\view\View;

/**
 * Class UserController
 *
 * @package ktc/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author  K. Dempsey
 * @author  T. Crompton
 * @author  C. Bishop
 */

class UserController extends Controller
{

    /**
     * User Index action
     *
     * If the user is logged in as "admin":
     * - Creates and uses a UserCollectionModel object to create a UserModel generator
     * - Creates and renders a userIndex template with the UserModel generator attached
     * Otherwise redirects to:
     * - UserController::loginAction if there is no user logged in
     * - HomeController::indexAction if the logged in user is not "admin"
     *
     * @uses $_SESSION['userName'] to determine if the logged in user is "admin"
     */
    public function indexAction()
    {
        session_start();
        if (isset($_SESSION['userName'])) {
            if ($_SESSION['userName'] == "admin") {
                try {
                    $collection = new UserCollectionModel();
                    $users = $collection->getUsers();
                    $view = new View('userIndex');
                    echo $view->addData('users', $users)->render();
                } catch (StoreException $ex) {
                    $view = new View('exception');
                    echo $view->addData("exception", $ex)->addData("back", "Home")->render();
                }
            } else {
                $this->redirect('welcome');
            }
        } else {
            $this->redirect('userLogin');
        }
    }

    /**
     * User Login action
     *
     * Either:
     * - Creates and renders a userLogin template to be filled by the user
     * or:
     * - Uses provided form data to create a UserModel and determine if correct credentials were provided
     * - If yes, begins a session and sets $_SESSION['userName'] $_SESSION['userId'] and $_SESSION['userFull']
     *   to values obtained from the UserModel
     *
     * @uses $_POST['login'] to determine whether to begin a session or create a userLogin
     * @uses $_POST['userName'] to set $user->load($name) and $_SESSION['userName']
     * @uses $_POST['password'] to determine if correct credentials have been provided
     */
    public function loginAction()
    {
        session_start();
        try {
            if (isset($_POST['login'])) {
                $user = new UserModel();
                $user->load($_POST['userName']);
                if (password_verify($_POST['password'], $user->getPassword())) {
                    session_start();
                    $_SESSION['userName'] = $user->getUserName();
                    $_SESSION['userId'] = $user->getId();
                    $_SESSION['userFull'] = $user->getFirstName() . " " . $user->getLastName();
                    $this->redirect('welcome');
                } else {
                    throw new StoreException(4); // Maybe not an exception?
                }
            } else {
                $view = new View('userLogin');
                echo $view->render();
            }
        } catch (StoreException $ex) {
            $view = new View('exception');
            echo $view->addData("exception", $ex)->addData("back", "userLogin")->render();
        }
    }

    /**
     * User Logout action
     *
     * If there is a user logged in, destroys their session
     * Otherwise redirects to HomeController::indexAction
     *
     * @uses $_SESSION['userName'] to determine if a user is logged in
     */
    public function logoutAction()
    {
        session_start();
        if (isset($_SESSION['userName'])) {
            session_unset();
            session_destroy();
        }
        $this->redirect('Home');
    }

    /**
     * User Create action
     *
     * Either:
     * - Creates and renders a userCreate template to be filled by the user
     * or:
     * - Creates a new UserModel based on the provided form data, begins a session if the UserModel
     *   was successfully added to the User table, and redirects to AccountController::indexAction
     *
     * @uses $_POST['create'] to determine whether to create a UserModel or a userCreate
     * @uses $_POST['userName'] to set $user->userName
     * @uses $_POST['firstName'] to set $user->firstName
     * @uses $_POST['lastName'] to set $user->lastName
     * @uses $_POST['password'] to set $user->password
     * @uses $_POST['email'] to set $user->email
     * @uses $_POST['phone'] to set $user->phone
     * @uses $_POST['dob'] to set $user->dateOfBirth
     */
    public function createAction()
    {
        session_start();
        try {
            if (isset($_POST['create'])) {
                $user = new UserModel();
                if ($user->check($_POST['userName'])) {
                    throw new StoreException(5);
                }
                $user->setUserName($_POST['userName']);
                $user->setFirstName($_POST['firstName']);
                $user->setLastName($_POST['lastName']);
                if (preg_match('/^(?=[a-zA-Z0-9]*[A-Z][a-zA-Z0-9]*)([a-zA-Z0-9]{7,14})$/', $_POST['password']) === 0) {
                    throw new StoreException(9);
                }
                if (!$passHash = password_hash($_POST['password'], PASSWORD_BCRYPT)) {
                    throw new StoreException(6);
                }
                $user->setPassword($passHash);
                $user->setEmail($_POST['email']);
                $user->save();
                $_SESSION['userName'] = $user->getUserName();
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userFull'] = $user->getFirstName() . " " . $user->getLastName();
                $this->redirect('welcome');
            } else {
                $view = new View('userCreate');
                echo $view->render();
            }
        } catch (StoreException $ex) {
            $view = new View('exception');
            echo $view->addData("exception", $ex)->addData("back", "userCreate")->render();
        }
    }

    /**
     * User Check action
     *
     * Used via AJAX to check if a username already exists in the database. Either:
     * - Echoes back "unique" if the username is not in use
     * or:
     * - Echoes back "not unique" if the username is in use
     * If there is an error, echoes back this instead
     *
     * @uses $_POST['checkName'] to determine which username to check for
     */
    public function checkAction()
    {
        if (!isset($_POST['checkName'])) {
            $this->redirect('welcome');
        }
        try {
            $user = new UserModel();
            if ($user->check($_POST['checkName'])) {
                echo "not unique";
            } else {
                echo "unique";
            }
        } catch (StoreException $ex) {
            echo $ex->getMessage();
        }
    }
}
