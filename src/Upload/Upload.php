<?php

namespace Code\Upload;

class Upload
{
    private $folder = '';

    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    public function doUpload($files = [])
    {
        $arrImagesName = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            $newImageName = $this->renameImage($files['name'][$i]);

            if (move_uploaded_file($files['tmp_name'][$i], $this->folder . $newImageName)) {
                $arrImagesName[] = $newImageName;
            }
        }

        return $arrImagesName;
    }

    private function renameImage($imgName)
    {
        return sha1($imgName . uniqid() . time());
    }
}