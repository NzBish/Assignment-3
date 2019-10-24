<?php
namespace ktc\a2\model;

use ktc\a2\Exception\StoreException;


class SearchCollectionModel extends Model
{
    private $prodIds;
    private $N;

    public function __construct($name)
    {
        parent::__construct();
        if (!$result = $this->db->query("SELECT `prod_id`, `prod_sku` FROM `product`
                                                WHERE `prod_name` LIKE '%$name%'
                                                ORDER BY `prod_sku` ASC;")) {
            throw new StoreException(99, 'DB query failed: ' . mysqli_error($this->db));
        }
        if ($result->num_rows < 1) {
            throw new StoreException(99, "No products containing '$name' found");
        }
        $this->prodIds = array_column($result->fetch_all(), 0);
        $this->N = $result->num_rows;
    }

    public function getResults()
    {
        foreach ($this->prodIds as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new ProductModel())->load($id);
        }
    }
}
