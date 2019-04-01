<?php
namespace App\Lib\Util;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Lib\Util\StringUtil;

class FileUpload
{
    private $uploadedFile = null;
    private $fileName = '';

    public function processUploadedFile(UploadedFile $uploadedFile)
    {
        if ($uploadedFile->isValid()) {
            $tmpUploadedFile = $uploadedFile->getPathname().'.'.$uploadedFile->guessClientExtension();
            $uploadedFile->move(storage_path().'/upload/', $tmpUploadedFile);
            $actualFile = explode('/', $tmpUploadedFile);
            $actualName = $actualFile[count($actualFile)-1];
            $this->fileName = storage_path().'/upload/'.$actualName;
        }
        return $this->fileName;
    }

    public function removeTemporaryFile() {
        unlink($this->fileName);
    }

}
