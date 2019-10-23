<?php
namespace ktc\a2\controller;

use ktc\a2\Exception\StoreException;
use ktc\a2\model\SearchCollectionModel;
use ktc\a2\view\View;

class SearchController extends Controller
{
    public function searchAction(){
         session_start();
        $output ='';

          if (isset($_SESSION['userName'])) {
        try {
            $collection = new SearchCollectionModel();
            $products = $collection->retrieveProduct($_POST["search"]);
            $output .= "<table>
        <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock on Hand</th>
            <th>Category</th>
        </tr>";

            foreach ($products as $prods){
                $output .= "
                        <tr>
                            <td>$prods[0]</td>
                            <td>$prods[1]</td>
                            <td>$prods[2]</td>
                            <td>$prods[3]</td>
                            <td>$prods[4]</td>
                            </tr>";
            }
            echo $output;
        } catch (StoreException $ex) {
            $view = new View('exception');
            echo $view->addData("exception", $ex)->addData("back", "Home")->render();
        }
           } else {
             $this->redirect('Home');
          }
    }
}


