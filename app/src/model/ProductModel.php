<?php
namespace ktc\a2\model;

use ktc\a2\Exception\StoreException;


class ProductModel extends Model
{

    private $id;

    private $sku;

    private $name;

    private $cost;

    private $category;

    private $quantity;

    public function getId()
    {
        return $this->id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `product` WHERE `prod_id` = $id;")) {
            throw new StoreException(99, 'DB query failed: '.mysqli_error($this->db));
        }
        if ($result->num_rows < 1) {
            throw new StoreException(99, 'No product found with ID '.$id);
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
