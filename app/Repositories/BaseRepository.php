<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Object_;

class BaseRepository
{

    protected $handleModel;
    public function delete(int $id)
    {
        return $this->handleModel::where('id', $id)->delete();
    }
    public function update(int $id, array $data)
    {
        return $this->handleModel::where('id', $id)->update($data);
    }
    // lấy ra đối tượng theo id truyền vào và select theo các trường mong muốn ( nếu không truyền mặc định select *)
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
        if (!empty($filters['search'])) {
            foreach ($filters['search'] as $field => $value) {
                if(!empty($value)) $query = $this->likeTextFilter($query, $field, $value);
            }
        }
        if (!empty($filters['absoluteFilter'])) {
            foreach ($filters['absoluteFilter'] as $field => $value) {
                if(!empty($value)) $query = $this->absoluteFilter($query, $field, $value);
            }
        }
        if (!empty($filters['between'])) {
            foreach ($filters['between'] as $field => $value) {
               if(!empty($value) && !empty($value['min'] && !empty($value['max']))){
                   $query = $this->between($query, $field, $value['min'], $value['max']);
                }
            }
        }
        if(!empty($filters['relation'])){
            foreach ($filters['relation'] as $key => $value) {
                if(!empty($value)){
                    $query = $query->with($key)->whereHas($key, function ($q) use ($value){
                        $q->where($value['field'],"like","%" . $value['value'] . "%");
                    });
                }
            }
        }
        if(!empty($filters['relationAbsolute'])){
            foreach ($filters['relationAbsolute'] as $key => $value) {
                if(!empty($value)){
                    $query = $query->with($key)->whereHas($key, function ($q) use ($value){
                        $q->where($value['field'], $value['value']);
                    });
                }
            }
        }
        return $query;
    }
    public function returnError($message){
       return [
           'status' => false,
           'message' => $message
       ];
    }
    public function findById($id){
        return $this->handleModel->findOrFail($id);
    }
      public function handleUploadOneFile(object $file)
    {
        $path = null;
        if ($file !== null) {
            $file_name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $file_name, 'public');
        }
        return $path;
    }
    // handle upload mutil file
    public function handleUploadMutilFile(array $files)
    {
        $path = [];
        if (!empty($files)) {
            foreach ($files as $key => $file) {
                $path[] = $this->handleUploadOneFile($file);
            }
        }
    }
    // chỉ cần truyền file mới và đường đẫn file cũ sẽ xử lý để xoá cũ thêm mới trả về đường dẫn file mới
   public function handleUpdateFile(object $file, ?string $old_file)
{
    if ($file instanceof \Illuminate\Http\UploadedFile) {
        if (!empty($old_file) && Storage::disk('public')->exists($old_file)) {
            $this->deleteFile($old_file);
        }
        return $this->handleUploadOneFile($file);
    }
    return $old_file;
}
    public function deleteFile(string $path)
    {
        if (isset($path) && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}