<?php

namespace BKTest\Database;

abstract class PDOWrapper
{
    /** @var \PDO */
    protected $db;

    const SELECT_STMT = '';
    const DELETE_STMT = '';
    /**
     * Constructor
     *
     * @param \PDO $database
     */
    public function __construct(\PDO $database)
    {
        $this->db = $database;
    }

    /**
     * Select some data by and identifier
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function selectById($id)
    {
        $stmt = $this->db->query(sprintf(static::SELECT_STMT, (int)$id));
        if ($stmt) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Delete a data record from storage
     *
     * @param mixed $id
     *
     * @return bool Success/Fail
     */
    public function delete($id)
    {
        return (bool) $this->db->query(sprintf(static::DELETE_STMT, (int)$id));
    }
}
