<?php
namespace BKTest\Database;

use BKTest\Models\iPersist;

/**
 * Class ArticlePdo
 *
 * @package BKTest\Database
 * @author Brian Kroll <me@bckroll.com>
 */
class ArticlePdo extends PDOWrapper implements iPersist
{
    protected $tableColumns = [
        'id' => 'int',
        'title' => 'string',
        'author' => 'string',
        'content' => 'string',
        'topic' => 'int'
    ];

    const INSERT_STMT = "INSERT INTO article (title, author, content, topic) VALUES (:title, :author, :content, :topic);";
    const SELECT_STMT = "SELECT id, title, author, content, topic FROM article WHERE id = %d;";
    const DELETE_STMT = "DELETE FROM article WHERE id = %d";
    const SELECT_ALL = "SELECT id, title, author, content, topic FROM article WHERE %s = %s";

    /**
     * Insert a data record
     *
     * @param array $data
     *
     * @return mixed Id of inserted record
     */
    public function insertRow(Array $data)
    {
        if (isset($data['title'], $data['author'], $data['content'], $data['topic'])) {
            $stmt = $this->db->prepare(self::INSERT_STMT);
            $success = $stmt->execute([
                ':title' => $data['title'],
                ':author' => $data['author'],
                ':content' => $data['content'],
                ':topic' => $data['topic'],
            ]);
            if ($success) {
                return $this->db->lastInsertId();
            } else {
                error_log(serialize($this->db->errorInfo()));
            }
        }

        return false;
    }

    /**
     * Select All records for which the given value matches the given attribute
     *
     * @param string $attribute
     * @param string $value
     *
     * @return mixed
     * @throws \Exception
     */
    public function selectAllByAttributeEquals($attribute, $value)
    {
        if (!isset($this->tableColumns[$attribute])) {
            throw new \Exception('Invalid input');
        }

        $stmt = $this->db->query(sprintf(self::SELECT_ALL, $attribute, $this->db->quote($value)));
        if ($stmt) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Return all stored records
     *
     * @return array
     * @throws \Exception
     */
    public function getAll()
    {
        throw new \Exception('Not implemented');
    }
}