<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

abstract class Controller
{

    protected $handleRepository;

    // handle upload only one file
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
    public function handleUpdateFile(object $file, string $old_file)
    {
        if ($file != null) {
            if (isset($old_file) && Storage::disk('public')->exists($old_file)) {
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
    public function returnInertiaList($data, $view)
    {
        return inertia($view, [
            'data' => $data
        ]);
    }
    public function returnInertia($data, $message, $route)
    {
        if (isset($data['status']) && $data['status'] == false) {
            return redirect()->back()->with('error', $data['message']);
        } else {
            return redirect()->route($route)->with('success', $message);
        }
    }
}
