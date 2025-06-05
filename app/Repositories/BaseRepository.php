<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    protected $handleModel;

    // public function create(array $data)
    // {
    //     return  $this->handleModel::create($data);
    // }
    public function delete(int $id)
    {
        return $this->handleModel::where('id', $id)->delete();
    }

    public function update(int $id, array $data)
    {
        return $this->handleModel::where('id', $id)->update($data);
    }

    public function getById(int $id, array $select = ['*'])
    {
        return $this->handleModel::where('id', $id)->select($select)->first();
    }

    public function likeTextFilter($query, $key, $value)
    {
        return $query->where($key, "like", '%' . $value . '%');
    }

    public function between($query, $key, $min, $max)
    {
        return $query->whereBetween($key, [$min, $max]);
    }

    public function absoluteFilter($query, $key, $value)
    {
        return $query->where($key, $value);
    }

    public function filterData($query, array $filters)
    {
        $allowedFields = $query->getModel()->getFillable();
        $query->select($query->getModel()->getTable() . '.*');

        if (!empty($filters['search'])) {
            foreach ($filters['search'] as $field => $value) {
                if (in_array($field, $allowedFields) && !empty($value)) {
                    $query = $this->likeTextFilter($query, $field, $value);
                }
            }
        }
        if (!empty($filters['absoluteFilter'])) {
            foreach ($filters['absoluteFilter'] as $field => $value) {
                if (in_array($field, $allowedFields) && !empty($value)) {
                    $query = $this->absoluteFilter($query, $field, $value);
                }
            }
        }
        if (!empty($filters['between'])) {
            foreach ($filters['between'] as $field => $value) {
                if (in_array($field, $allowedFields) && !empty($value) && !empty($value['min']) && !empty($value['max'])) {
                    $query = $this->between($query, $field, $value['min'], $value['max']);
                }
            }
        }
        if (!empty($filters['relation'])) {
            foreach ($filters['relation'] as $key => $value) {
                if (!empty($value)) {
                    $query = $query->with($key)->whereHas($key, function ($q) use ($value) {
                        $q->select('id')->where($value['field'], "like", "%" . $value['value'] . "%");
                    });
                }
            }
        }
        if (!empty($filters['relationAbsolute'])) {
            foreach ($filters['relationAbsolute'] as $key => $value) {
                if (!empty($value)) {
                    $query = $query->with($key)->whereHas($key, function ($q) use ($value) {
                        $q->select('id')->where($value['field'], $value['value']);
                    });
                }
            }
        }
        return $query;
    }

    public function returnError($message)
    {
        return [
            'status' => false,
            'message' => $message
        ];
    }

    public function findById($id)
    {
        return $this->handleModel::findOrFail($id);
    }
}