<?php
namespace ktc\a2\model;

use ktc\a2\Exception\StoreException;


/**
 * Class ProductModel
 * @package ktc\a2\model
 */
class ProductModel extends Model
{

    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $sku;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $cost;

    /**
     * @var
     */
    private $category;

    /**
     * @var
     */
    private $quantity;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param $sku
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param $id
     * @return $this
     * @throws StoreException
     */
    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `product` WHERE `prod_id` = $id;")) {
            throw new StoreException(99, 'DB query failed: ' . mysqli_error($this->db));
        }
        if ($result->num_rows < 1) {
            throw new StoreException(99, 'No product found with ID ' . $id);
        }
        if ($result->num_rows == 1) {
            $result = $result->fetch_assoc();
            $this->id = $id;
            $this->sku = $result['prod_sku'];
            $this->name = $result['prod_name'];
            $this->cost = $result['prod_cost'];
            $this->category = $result['prod_category'];
            $this->quantity = $result['prod_quantity'];
        } else {
            throw new StoreException(99, 'Transaction ID ' . $id . ' is not unique');
        }

        return $this;
    }

    /**
     * @return $this
     * @throws StoreException
     */
    public function save()
    {
        $id = $this->id;
        $sku = $this->sku ?? "NULL";
        $name = $this->name ?? "NULL";
        $cost = $this->cost ?? "NULL";
        $category = $this->category ?? "NULL";
        $quantity = $this->quantity ?? "NULL";
        if (!isset($this->id)) {
            /** New transaction - Perform INSERT */
            if (!$result = $this->db->query("INSERT INTO `product` VALUES
                                          (NULL,'$sku','$name','$cost','$category','$quantity');")) {
                throw new StoreException(99, "Insert product failed");
            }
            $this->id = $this->db->insert_id;
        } else {
            // saving existing user - perform UPDATE
            if (!$result = $this->db->query("UPDATE `product` SET
                                        `prod_sku` = '$sku',
                                        `prod_name` = '$name',
                                        `prod_cost` = '$cost',
                                        `prod_category` = '$category',
                                        `prod_quantity` = '$quantity'                                        
                                         WHERE `id` = $id;")) {
                throw new StoreException(7);
            }
        }

        return $this;
    }
}
