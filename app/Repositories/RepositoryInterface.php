<?php
namespace App\Repositories;

interface RepositoryInterface {
    public function getAll(int $pagination = 10, array $select = ['*']);
    public function update(int $id,array $data);
    public function create(array $data);
    public function delete (int $id);
    public function getById(int $id);

}