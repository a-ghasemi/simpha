<?php

namespace Kernel\Abstractions;

interface ISqlDb
{
    public function singleSelect(string $table, ?array $fields, $where_clause);

    public function multiSelect(string $table, ?array $fields, $where_clause);

    public function generatorSelect(string $table, ?array $fields, $where_clause);

    public function whereLike(array $where_clause);

    public function insert(string $table, array $fields);

    public function update(string $table, array $fields, $where_clause);

    public function insertOrUpdate(string $table, array $fields, $where_clause);

    public function rawQuery(string $sql);

    public function increase(string $table, array $counter_fields, $where_clause);

}