<?php
namespace ktc\a2\controller;

use ktc\a2\Exception\StoreException;
use ktc\a2\model\ProductModel;
use ktc\a2\model\ProductCollectionModel;
use ktc\a2\view\View;
/**
 * Class ProductController
 *
 * @package ktc/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author  K. Dempsey
 * @author  T. Crompton
 * @author  C. Bishop
 */

class ProductController extends Controller
{


    public function indexAction()
    {
        session_start();
        if (isset($_SESSION['userName'])) {
            try {
                $collection = new ProductCollectionModel();
                $products = $collection->getProducts();
                $view = new View('productIndex');
                echo $view->addData('products', $products)->render();
            } catch (StoreException $ex) {
                $view = new View('exception');
                echo $view->addData("exception", $ex)->addData("back", "Home")->render();
            }
        } else {
            $this->redirect('Home');
        }
    }
}
