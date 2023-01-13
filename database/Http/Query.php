<?php

namespace database\Http;

use PDO;

class Query
{
    /*
     * Table in the database.
     */
    private $table;

    /**
     * Columns in the table. Default to all.
     *
     * @var string
     */
    private $fields = '*';

    /**
     * SQL where query.
     *
     * @var null
     */
    private $where = null;

    /**
     * Stored arguments
     *
     * @var array
     */
    private $args = [];

    /**
     * SQL raw query
     *
     * @var string
     */
    private $sql = '';

    /**
     * Set the table property.
     *
     * @param string $table
     * @return Query
     */
    public static function table(string $table): Query
    {
        $query = new Query;
        $query->table = $table;
        return $query;
    }

    /**
     * Set the columns we want to fetch.
     *
     * @param array|null $fields
     * @return $this
     */
    public function select(array $fields = null): Query
    {
        if (isset($fields)) $this->fields = implode(', ', $fields);

        return $this;
    }

    /**
     * Set the where clause in the query.
     *
     * @param array $conditions
     * @return $this
     */
    public function where(array $conditions): Query
    {
        foreach ($conditions as $condition) {
            if (isset($this->where))
                $this->where .= " AND $condition[0] $condition[1] ?";
            else
                $this->where = "$condition[0] $condition[1] ?";
            $this->args[] = $condition[2];
        }

        return $this;
    }

    /**
     * Get all data from a model.
     *
     * @return array
     */
    public function get(): array
    {
        $this->sql = 'SELECT ' . $this->fields . ' FROM ' . $this->table;

        if (isset($this->where)) {
            $this->sql .= " WHERE $this->where";
        }

        $db = Connection::getConnection();
        $statement = $db->prepare($this->sql);

        if ($statement->execute($this->args)) return $statement->fetchAll(PDO::FETCH_OBJ);

        return [];
    }

    /*
     * Get the model that owns the other model (one-to-one).
     * Similar to Laravel's belongsTo()
     * https://laravel.com/docs/9.x/eloquent-relationships#one-to-many-inverse
     */
    public function one()
    {
        $this->sql = 'SELECT ' . $this->fields . ' FROM ' . $this->table;

        if (isset($this->where)) {
            $this->sql .= " WHERE $this->where";
        }

        $this->sql .= " LIMIT 1;";

        $db = Connection::getConnection();
        $statement = $db->prepare($this->sql);

        if ($statement->execute($this->args)) return $statement->fetch(PDO::FETCH_OBJ);

        return null;
    }
}
