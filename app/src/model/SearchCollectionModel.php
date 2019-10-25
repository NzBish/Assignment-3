<?php
namespace ktc\a2\model;

use ktc\a2\Exception\StoreException;


/**
 * Class SearchCollectionModel
 * @package ktc\a2\model
 */
class SearchCollectionModel extends Model
{
    /**
     * @var array Contains product IDs for lookup in SearchCollectionModel::getResults
     */
    private $prodIds;

    /**
     * @var int The number of indices in $prodIds
     */
    private $N;

    /**
     * SearchCollectionModel constructor
     *
     * Creates a SearchCollectionModel, which is used to create a generator for ProductModels matching a specific search term
     *
     * @param $name The search parameter which products in the database must match
     * @throws StoreException on database connection errors or lack of products matching the search term
     */
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

    /**
     * Get results
     *
     * A generator function yielding one ProductModel per ID in $prodIds
     *
     * @return \Generator|ProductModel[] Products
     * @throws StoreException via ProductModel->load
     * @uses \ktc\a2\model\SearchCollectionModel::$prodIds to create ProductModels
     */
    public function getResults()
    {
        foreach ($this->prodIds as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new ProductModel())->load($id);
        }
    }
}
