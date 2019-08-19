<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Dflydev\ApacheMimeTypes\PhpRepository;


class UploadManager
{
    protected $disk;
    protected $mineDetect;

    public function __construct(PhpRepository $mineDetect)
    {
        $this->disk = Storage::disk(config('blog.upload.stroage'));
        $this->mineDetect = $mineDetect;
    }

        /**
     * Return files and directories within a folder
     *
     * @param string $folder
     * @return array of [
     *     'folder' => 'path to current folder',
     *     'folderName' => 'name of just current folder',
     *     'breadCrumbs' => breadcrumb array of [ $path => $foldername ]
     *     'folders' => array of [ $path => $foldername] of each subfolder
     *     'files' => array of file details on each file in folder
     * ]
     */
    public function folderInfo($folder)
    {
        $folder =$this->cleanFolder($folder);

        $breadcrumbs = $this->breadcrumbs($folder);
        $slice = array_slice($breadcrumbs,-1);
        $foldername = current($slice);
        $breadcrumbs = array_slice($breadcrumbs,0,-1);

        $subfolders = [];
        foreach(array_unique($this->disk->directories($folder)) as $folder){
            $subfolders["/$subfolders"] = basename($subfolders);
        }

        $files = [];
        foreach($this->disk->files($folder) as $path){
            $files[] = $this ->fileDetails($path);
        }

        return cpmpact(
            'folder',
            'folderName',
            'breadcrumbs',
            'subfolders',
            'files'
        );

    }

    protected function breadcrumbs($folder)
    {
        $folder = trim($folder,'/');
        $crumbs = ['/' =>'root'];

        if(empty($folder)){
            return $crumbs;
        }

        $folders = explode('/',$folder);
        $build = '';
        foreach($folders as $folder)
        {
            $build .= '/' . $folder;
            $crumbs[$build] = $folder;
        }
    }

    /**
     * 返回文件详细信息数组
     */
    protected function fileDetails($path)
    {
        $path = '/' . ltrim($path, '/');

        return [
            'name' => basename($path),
            'fullPath' => $path,
            'webPath' => $this->fileWebpath($path),
            'mimeType' => $this->fileMimeType($path),
            'size' => $this->fileSize($path),
            'modified' => $this->fileModified($path),
        ];
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('blog.uploads.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }

    /**
     * 返回文件MIME类型
     */
    public function fileMimeType($path)
    {
        return $this->mimeDetect->findType(
            pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
     * 返回文件大小
     */
    public function fileSize($path)
    {
        return $this->disk->size($path);
    }

    /**
     * 返回最后修改时间
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(
            $this->disk->lastModified($path)
        );
    }

}