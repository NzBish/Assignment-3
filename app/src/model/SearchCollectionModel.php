<?php
namespace ktc\a2\model;

use ktc\a2\Exception\StoreException;


class SearchCollectionModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function retrieveProduct($name)
    {
        if (!$result = $this->db->query("SELECT * FROM `product`
                                                WHERE `prod_name` LIKE '%$name%'
                                                ORDER by `prod_sku` ASC;")) {
            throw new StoreException(99, 'No product found');
        }
        return $result->fetch_all();
    }
}
