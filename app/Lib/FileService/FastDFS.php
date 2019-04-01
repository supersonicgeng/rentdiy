<?php
namespace App\Lib\FileService;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Lib\Util\FileUpload;

class FastDFS
{
    private $tracker = null;
    private $storage = null;
    private $errorCode = 0;
    private $errorMessage = null;

    public function __construct()
    {
        $this->initTrackerConnection();
        $this->initStorage();
    }

    public function getTracker()
    {
        return $this->tracker;
    }

    private function initTrackerConnection()
    {
        try {
            $this->tracker = fastdfs_tracker_get_connection();
            if (!fastdfs_active_test($this->tracker)) {
                $this->errorCode = fastdfs_get_last_error_no();
                $this->errorMessage = fastdfs_get_last_error_info();
            }
        } catch (ErrorException $err) {
            $this->errorCode = fastdfs_get_last_error_no();
            $this->errorMessage = fastdfs_get_last_error_info();
        }
    }

    private function initStorage()
    {
        try {
            $this->storage = fastdfs_tracker_query_storage_store();
            if (!fastdfs_active_test($this->tracker)) {
                $this->errorCode = fastdfs_get_last_error_no();
                $this->errorMessage = fastdfs_get_last_error_info();
            }
        } catch (ErrorException $err) {
            $this->errorCode = fastdfs_get_last_error_no();
            $this->errorMessage = fastdfs_get_last_error_info();
        }
    }


    public function getStorage()
    {
        return $this->storage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function directSaveFile(UploadedFile $uploadedFile)
    {
        $fileUpload = new FileUpload();
        $resultFile = $fileUpload->processUploadedFile($uploadedFile);
        $result = $this->saveFile($resultFile);
        $fileUpload->removeTemporaryFile();
        return $result;
    }

    public function saveFile($filePath)
    {
        $saveResult = fastdfs_storage_upload_by_filename($filePath, null, array(), null, $this->getTracker(), $this->getStorage());

        if ($saveResult) {
            $result = [
                'status' => 0,
                'info'   => 'OK',
                'group'  => $saveResult['group_name'],
                'fileName' => $saveResult['filename'],
            ];
        } else {
            $this->errorCode = fastdfs_get_last_error_no();
            $this->errorMessage = fastdfs_get_last_error_info();
            $result = [
                'status' => $this->errorCode,
                'info'   => $this->errorMessage,
            ];
        }
        return $result;
    }

    public function deleteFile($fileId)
    {
        $deleteResult = fastdfs_storage_delete_file1($fileId, $this->getTracker(), $this->getStorage());
        if ($deleteResult) {
            $result = [
                'status' => 0,
                'info'   => 'OK',
            ];
        } else {
            $this->errorCode = fastdfs_get_last_error_no();
            $this->errorMessage = fastdfs_get_last_error_info();
            $result = [
                'status' => $this->errorCode,
                'info'   => $this->errorMessage,
            ];
        }
        return $result;
    }

    public static function getFullUrl($fileId)
    {
        $file = $fileId;
        if (substr($file, 0, 1) == '/') {
            $file = substr($file, 1);
        }
        $fields = explode('/', $file);
        if (count($fields) > 0) {
            $groupname = $fields[0];
            $fdfsServers = config('fastdfs.track_server_list');
            return 'http://'.$fdfsServers[$fields[0]].'/'.$file;
        } else {
            return '';
        }
    }
}
 ?>
