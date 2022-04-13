<?php

namespace Kernel;

use Kernel\Abstractions\AbsDbConnection;

class MysqlDbConnection extends AbsDbConnection
{
    protected $connection;

    protected function connect()
    {
        try {
            $this->connection = new \mysqli(
                $this->env_engine->get('DB_HOST', 'localhost'),
                $this->env_engine->get('DB_USER'),
                $this->env_engine->get('DB_PASS'),
                $this->env_engine->get('DB_NAME'),
                (int)$this->env_engine->get('DB_PORT', 3306),
            );
        } catch (\Exception $e) {
            $this->error_handler->addError('MySql Database Connection Failed!', $e->getMessage());
            $this->error_handler->throwError();
        }

        if ($this->connection->connect_error) {
            $this->error_handler->addError('MySql Database Connection Failed!', $this->connection->connect_error);
            $this->error_handler->throwError();
            return;
        }

        $this->connection->autocommit(!$this->on_demand);
        $this->state = 'connected';
    }

    public function disconnect()
    {
        if ($this->state !== 'connected') return;
        $this->connection->close();
    }

    public function commit()
    {
        if ($this->on_demand) return true;

        if (!$this->connection->commit()) {
            $this->error_handler->addError(
                'Transaction commit failed',
                $this->connection->connect_error
            );
            return false;
        }

        return true;
    }

    public function beginTransaction()
    {
        if ($this->on_demand) return true;

        if (!$this->connection->begin_transaction()) {
            $this->error_handler->addError(
                'Begin Transaction failed',
                $this->connection->connect_error
            );
            return false;
        }

        return true;
    }

    public function rollback()
    {
        if ($this->on_demand) return false;

        if (!$this->connection->rollback()) {
            $this->error_handler->addError(
                'Rollback Transaction failed',
                $this->connection->connect_error
            );
            return false;
        }

        return true;
    }

    public function singleSelect(string $table, ?array $fields, $where_clause)
    {
        $content = !empty($fields) ? '`' . implode('`,`', $fields) . '`' : '*';

        if (is_array($where_clause)) $where_clause = $this->stringifyWhereClause($where_clause);

        $sql = "SELECT *" .
            " FROM $table" .
            " WHERE $where_clause LIMIT 1;";

        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function multiSelect(string $table, ?array $fields, $where_clause)
    {
        $content = !empty($fields) ? '`' . implode('`,`', $fields) . '`' : '*';

        if (is_array($where_clause)) $where_clause = $this->stringifyWhereClause($where_clause);

        $sql = "SELECT $content" .
            " FROM `$table`" .
            " WHERE $where_clause;";

        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public function generatorSelect(string $table, ?array $fields, $where_clause)
    {
        $content = !empty($fields) ? '`' . implode('`,`', $fields) . '`' : '*';

        if (is_array($where_clause)) $where_clause = $this->stringifyWhereClause($where_clause);

        $sql = "SELECT $content" .
            " FROM $table" .
            " WHERE $where_clause LIMIT 1;";

        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                yield $row;
            }
        } else {
            return null;
        }
    }

    protected function stringifyWhereClause($where_clause)
    {
        $w_str = [];
        foreach ($where_clause as $field => $val) {
            $w_str[] = "`$field` = '$val'";
        }
        $w_str = implode(" AND ", $w_str);
        return $w_str;
    }

    public function insert(string $table, array $fields): ?bool
    {
        $sql = "INSERT INTO `$table`" .
            " (`" . implode('`,`', array_keys($fields)) . "`)" .
            " VALUES ('" . implode("','", array_values($fields)) . "');";

        $this->connection->query($sql);
        return $this->connection->insert_id;
    }

    public function update(string $table, array $fields, $where_clause): ?bool
    {
        $content = [];
        foreach ($fields as $key => $val) {
            $content[] = "`$key` = '$val'";
        }
        $content = implode(',', $content);

        if (is_array($where_clause)) $where_clause = $this->stringifyWhereClause($where_clause);

        $sql = "UPDATE `$table`" .
            " SET $content" .
            " WHERE $where_clause;";
        $this->connection->query($sql);
        return true;
    }

    public function insertOrUpdate(string $table, array $fields, $where_clause): ?bool
    {
        $record = $this->oneSelect($table, $fields, $where_clause);
        if (is_null($record)) { //insert
            return $this->insert($table, $fields);
        } else { //update
            return $this->update($table, $fields, $where_clause);
        }
    }

    public function rawQuery(string $sql)
    {
        return $this->connection->query($sql);
    }

    public function increase(string $table, array $counter_fields, $where_clause): ?bool
    {
        $record = $this->oneSelect($table, $counter_fields, $where_clause);

        if (is_null($record)) { //insert
            return $this->insert($table, array_merge(array_combine($counter_fields, str_split(str_repeat('1', count($counter_fields)))), $where_clause));
        } else { //update
            $values = [];
            foreach ($counter_fields as $field) {
                $values[$field] = ((int)$record[$field]) + 1;
            }
            return $this->update($table, $counter_fields, $where_clause);
        }
    }

    public function dropTable(string $table): ?bool
    {
        $sql = "DROP TABLE IF EXISTS $table;";
        $this->connection->query($sql);
        return true;
    }

    public function createTable(string $table, array $fields): ?bool
    {
        $sql = "CREATE TABLE IF NOT EXISTS $table " .
            " (" . implode(',', $fields) . ");";
        $this->connection->query($sql);
        return true;
    }

    public function hasTable(string $table): ?bool
    {
        $result = $this->raw("SHOW TABLES LIKE '$table';");
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function showTablesLike(string $table): ?string
    {
        $query_string = $table;
        do {
            $result = $this->raw("SHOW TABLES LIKE '$query_string';");
            $query_string .= '_';
        } while ($result->num_rows <= 0);

        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            $result = array_values($result)[0];
            return $result;
        } else {
            return null;
        }
    }

    public function getTableColumns(string $table): ?array
    {
        $columns = [];
        $result = $this->raw("SHOW COLUMNS FROM `$table`;");
        if ($result->num_rows > 0)
            while ($res = $result->fetch_assoc()) {
                $columns[$res['Field']] = array_filter($res, function ($k) use ($res) {
                    return $k != $res['Field'];
                });
            }
        else {
            return null;
        }
        return $columns;
    }

    public function whereLike(array $where_clause)
    {
        // TODO: Implement whereLike() method.
    }
}