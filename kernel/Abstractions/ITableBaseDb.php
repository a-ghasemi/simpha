<?php

namespace Kernel\Abstractions;

interface ITableBaseDb
{
    public function createTable(string $table, array $fields);

    public function hasTable(string $table);

    public function dropTable(string $table);

    public function showTablesLike(string $table);

    public function getTableColumns(string $table);

}