<?php
namespace ktc\a2\model;

use ktc\a2\Exception\StoreException;

/**
 * Class UserCollectionModel
 *
 * @package ktc/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author  K. Dempsey
 * @author  T. Crompton
 * @author  C. Bishop
 */
class UserCollectionModel extends Model
{
    /**
     * @var array Contains user IDs for lookup in UserCollectionModel::getUsers
     */
    private $userNames;

    /**
     * @var int The number of indices in $userNames
     */
    private $N;

    /**
     * UserCollectionModel constructor
     *
     * Creates a UserCollectionModel, which is used to create a generator for UserModels
     *
     * @throws StoreException on database connection errors or lack of users in the User table
     */
    public function __construct()
    {
        parent::__construct();
        if (!$result = $this->db->query("SELECT `user_name` FROM `user`;")) {
            throw new StoreException(99, 'DB query failed: ' . mysqli_error($this->db));
        }
        if ($result->num_rows < 1) {
            throw new StoreException(99, 'User db table is empty');
        }
        $this->userNames = array_column($result->fetch_all(), 0);
        $this->N = $result->num_rows;
    }

    /**
     * Get users
     *
     * A generator function yielding one UserModel per ID in $userNames
     *
     * @return \Generator|UserModel[] Users
     * @throws StoreException via UserModel->load
     *@uses \ktc\a2\model\UserCollectionModel::$userNames to create UserModels
     */
    public function getUsers()
    {
        foreach ($this->userNames as $name) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new UserModel())->load($name);
        }
    }
}
