<?php
namespace BKTest\Database;

use BKTest\Models\iPersist;
/**
 * Created by PhpStorm.
 * User: Brian
 * Date: 04/09/2015
 * Time: 20:56
 */
class TopicPdo extends PDOWrapper implements iPersist
{
    protected $tableColumns = [
        'id' => 'int',
        'title' => 'string'
    ];

    const SELECT_STMT = "SELECT id, title FROM topic WHERE id = %d;";
    const DELETE_STMT = "DELETE FROM topic WHERE id = %d;";
    const INSERT_STMT = "INSERT INTO topic (title) VALUES (:title)";

    public function getTable()
    {
        return 'topic';
    }

    /**
     * Insert a data record
     *
     * @param array $data
     *
     * @return mixed Id of inserted record
     */
    public function insertRow(Array $data)
    {
        if (isset($data['title'])) {
            $stmt = $this->db->prepare(self::INSERT_STMT);
            if ($stmt->execute([':title' => $data['title']])) {
                return $this->db->lastInsertId();
            }
            error_log(serialize($stmt->errorInfo()));
        }
        return false;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return mixed
     * @throws \Exception
     */
    public function selectAllByAttributeEquals($attribute, $value)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Return all stored records
     *
     * @return array
     */
    public function getAll()
    {
        $stmt = $this->db->query("SELECT id, title FROM topic;");
        if ($stmt) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return false;
    }
}