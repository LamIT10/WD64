<?php
namespace App\Services;


interface ServiceInterface{
    public function create(array $data);
    public function uploadOneFile($file);
    public function getById(int $id);
    public function handleUpdateFile($file,$old_file);
    public function deleteFile(string $path);
    public function update(int $id,array $data);
    public function delete (int $id);
}
