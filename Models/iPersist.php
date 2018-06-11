<?php
namespace BKTest\Models;

/**
 * Interface iPersist
 *
 * @package BKTest\Models
 * @author Brian Kroll <me@bckroll.com>
 */
interface iPersist
{
    /**
     * Select some data by and identifier
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function selectById($id);

    /**
     * Insert a data record
     *
     * @param array $data
     *
     * @return mixed Id of inserted record
     */
    public function insertRow(Array $data);

    /**
     * Delete a data record from storage
     *
     * @param mixed $id
     *
     * @return bool Success/Fail
     */
    public function delete($id);

    /**
     * Select All records for which the given value matches the given attribute
     *
     * @param string $attribute
     * @param string $value
     *
     * @return mixed
     */
    public function selectAllByAttributeEquals($attribute, $value);

    /**
     * Return all stored records
     *
     * @return array
     */
    public function getAll();
}