<?php

namespace App\Repositories;

class Repository implements RepositoryInterface
{

    protected $model;

    public function __construct($repository)
    {
        $this->model = $repository;
    }
    public function getAll(int $pagination = 10, array $select = ['*'])
    {
        if ($pagination == 0) {
            return $this->model::select($select)->all();
        }
        return $this->model::select($select)->paginate($pagination);
    }
    public function getById(int $id)
    {
        return $this->model::find($id);
    }
    public function create(array $data)
    {
        return $this->model::create($data);
    }
    public function update($id, $data)
    {
        return $this->model::where('id', $id)->update($data);
    }
    public function delete(int $id)
    {
        return $this->model::where('id', $id)->delete();
    }
}
