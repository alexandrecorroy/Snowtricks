<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/05/2018
 * Time: 13:41
 */

namespace App\Service;

class RemoveFile
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }


    public function remove($file)
    {
        if (file_exists($this->getTargetDirectory().'/'.$file))
        {
            unlink($this->getTargetDirectory().'/'.$file);
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
