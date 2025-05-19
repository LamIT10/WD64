<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class Service implements ServiceInterface
{
    protected $repository;

    public function __construct($repo)
    {
        $this->repository = $repo;
    }

    public function create(array $data)
    {
        $this->repository->create($data);
    }
    public function update($id, $data){
        $this->repository->update($id, $data);
    }
    // handle upload only one file
    public function uploadOneFile($file)
    {
        $path = null;
        if ($file !== null) {
            $file_name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $file_name, 'public');
        }
        return $path;
    }
    // handle update file and handle old file
    public function handleUpdateFile($file, $old_file)
    {
        if ($file != null) {
            if (isset($old_file) && Storage::disk('public')->exists($old_file)) {

                $this->deleteFile($old_file);
            }
            return $this->uploadOneFile($file);
        }
        return $old_file;
    }
    // delete file by path
    public function deleteFile(string $path)
    {
        return Storage::disk('public')->delete($path);
    }

    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }
    public function delete (int $id){
        return $this->repository->delete($id);
    }

}
