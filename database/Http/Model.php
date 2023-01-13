<?php

namespace database\Http;

abstract class Model
{
    /**
     * Table name in the database.
     *
     * @var null|string
     */
    protected static $table;

    /**
     * We can set the primary key either uuid or id.
     *
     * @var string
     */
    protected static $primaryKey = 'id';

    /**
     * Get column attributes.
     *
     * @var array
     */
    private $_attributes = [];

    /**
     * Return the table property.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$table;
    }

    /**
     * Return the primary key property.
     *
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return static::$primaryKey;
    }

    /**
     * Get the values of the current object.
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes))
            return $this->_attributes[$name];

        if (method_exists($this, $name))
            return $this->$name();

        return null;
    }

    /**
     * Set the values of the current object.
     *
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * @param array|null $attributes
     */
    public function __construct(array $attributes = null)
    {
        if (!is_null($attributes)) $this->_attributes = $attributes;
    }

    /**
     * Return all values in the model.
     * We use the Query class the make the queries.
     *
     * @return array
     */
    public static function all(): array
    {
        $data = Query::table(static::$table)->select()->get();

        $objects = [];
        foreach ($data as $object) {
            $objects[] = new static((array)$object);
        }

        return $objects;
    }

    /**
     * Return only one model.
     * We can provide the fields to be selected in the table as well.
     *
     * @param $id
     * @param array|null $fields
     * @return array
     */
    public static function find($id, array $fields = null): array
    {
        if (!is_array($id))
            $data = Query::table(static::$table)->select($fields)->where([[static::$primaryKey, "=", $id]])->get();
        else {
            if (is_array($id[0]))
                $data = Query::table(static::$table)->select($fields)->where($id)->get();
            else
                $data = Query::table(static::$table)->select($fields)->where([$id])->get();
        }

        $objects = [];
        foreach ($data as $object) {
            $objects[] = new static((array)$object);
        }

        return $objects;
    }

    /**
     * Return only one model.
     * Similar to find() method, but we can use first() to return the relation to other model.
     *
     * @param $id
     * @param array|null $fields
     * @return static|null
     */
    public static function first($id, array $fields = null): ?Model
    {
        if (!is_array($id))
            $data = Query::table(static::$table)->select($fields)->where([[static::$primaryKey, "=", $id]])->one();
        else {
            if (is_array($id[0]))
                $data = Query::table(static::$table)->select($fields)->where($id)->one();
            else
                $data = Query::table(static::$table)->select($fields)->where([$id])->one();
        }

        if ($data) return new static((array)$data);

        return null;
    }

    /**
     * Get the model that owns the other model (one-to-one).
     * Similar to Laravel's belongsTo()
     * https://laravel.com/docs/9.x/eloquent-relationships#one-to-many-inverse
     *
     * @param string $class_name
     * @param string $foreign_key
     * @return mixed
     */
    public function belongsTo(string $class_name, string $foreign_key)
    {
        $class = null;

        if (!is_null($this->$foreign_key)) {
            $class = new $class_name();

            $foreign_data = Query::table($class::$table)->select(null)->where([[$class::$primaryKey, "=", $this->$foreign_key]])->one();

            $class = new $class_name((array)$foreign_data);
        }

        return $class;
    }

    /**
     * Get all the models of the parent model (one-to-many).
     * Similar to Laravel's hasMany()
     * https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     *
     * @param string $class_name
     * @param string $foreign_key
     * @return array
     */
    public function hasMany(string $class_name, string $foreign_key): array
    {
        $class = new $class_name();

        $foreign_data = Query::table($class::$table)->select()->where([[$foreign_key, "=", $this->_attributes[static::$primaryKey]]])->get();

        $objects = [];
        foreach ($foreign_data as $object) {
            $objects[] = new $class_name((array)$object);
        }

        return $objects;
    }

    /**
     * Get all the stored attributes.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->_attributes;
    }
}
