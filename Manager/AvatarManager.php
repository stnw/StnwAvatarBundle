<?php
namespace Stnw\AvatarBundle\Manager;

/**
 * Class for generating avatars.
 *
 * @author Studenikin Sergey <studenikin.s@gmail.com>
 *
 */
class AvatarManager
{
    protected $malefolders;
    protected $femalefolders;
    protected $dir = ''; //the dir with male & and female picture templates.
    protected $frame = false; //the general canvas.

    /**
     * @param array $malefolders - array with male's names of avatar's layers (array("face", "hair", "nose", "eye", "mouth"))
     * @param array $femalefolders - array with female's names of avatar's layers (array("face", "hair", "nose", "eye", "mouth"))
     * @param string $dir - whole patch to male & female folders with pictures.
     */
    public function __construct($malefolders, $femalefolders, $dir)
    {
        $this->malefolders = $malefolders;
        $this->femalefolders = $femalefolders;

        if (substr($dir, -1) != "/" || substr($dir, -1) != "\\") {
            $dir = $dir . DIRECTORY_SEPARATOR;
        }
        $this->dir = $dir;
    }

    /**
     * Generate avatar and returns it.
     * It's useful to use in a browser (For example: <img src="avatar_generator.php?gender=male&size=100" />)
     *
     * @param string $gender ("female" or "male")
     * @param int $size (in pixels)
     */
    public function getAvatar($gender = null, $size = null)
    {
        $this->makeAvatar($gender, $size);
        header('Content-Type: image/png');
        imagepng($this->frame);
        imagedestroy($this->frame);
    }

    /**
     * Generate an avatar and save it in the url
     *
     * @param string $url - whole path for new avatar
     * @param string $gender ("f" or "m")
     * @param int $size (in pixels)
     */
    public function generateAvatar($url, $gender = null, $size = null)
    {
        $this->makeAvatar($gender, $size);
        $result = imagepng($this->frame, $url);
        imagedestroy($this->frame);

        return $result;
    }

    /**
     * Add to the picture the new layer (face, hairs, eyes etc)
     *
     * @param string $folder - the patch to the folder with layer's files
     */
    private function addAvatarLayer($folder)
    {
        $files = $this->listFiles($folder, "png");
        if ($files) {
            $layer = imagecreatefrompng($folder . $files[array_rand($files)]);
            imagealphablending($layer, true);
            imagesavealpha($layer, true);

            if (!$this->frame) {
                $this->frame = $layer;
            } else {
                $w = imagesx($this->frame);
                $h = imagesy($this->frame);
                imagecopy($this->frame, $layer, 0, 0, 0, 0, $w, $h);
                imagedestroy($layer);
            }
        }
    }

    /**
     * Generate an avatar and save it in the variable $this->frame.
     *
     * @param string $gender ("male" or "female")
     */
    private function makeAvatar($gender = "male", $size = null)
    {
        $this->frame = false;
        if ($gender == "f" or $gender == "female") {
            $gender = "female";
            $scanfolders = $this->femalefolders;
        } else {
            $gender = "male";
            $scanfolders = $this->malefolders;
        }

        foreach ($scanfolders as $folder) {
            $folderPatch = $folder = $this->dir . $gender . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
            $this->addAvatarLayer($folderPatch);
        }

        if ($size) {
            $this->resizePicture($size);
        }
    }

    /**
     * Makes resize of the picture
     *
     * @param $size - the size of the picture
     * @return bool
     */
    private function resizePicture($size)
    {
        $newSize = intval($size);
        $currentSize = imagesx($this->frame);
        if ($newSize > $currentSize || $newSize == 0) {
            return false;
        }

        $tmp = imageCreateTrueColor($newSize, $newSize);
        imageAlphaBlending($tmp, false);
        imageSaveAlpha($tmp, true);
        imageCopyResampled($tmp, $this->frame, 0, 0, 0, 0, $newSize, $newSize, $currentSize, $currentSize);
        $this->frame = $tmp;

        return true;
    }

    /**
     * Returns array with the names of files in the folder.
     *
     * @param $directory
     * @param $extension
     * @return array
     */
    public function listFiles($directory, $extension)
    {
        $files = array();
        $iterator = new \DirectoryIterator($directory);

        foreach ($iterator as $file) {
            if (!$file->isDot() && substr($file->getFilename(), -strlen($extension)) === $extension) {
                $files[] = $file->getFilename();
            }
        }

        return $files;
    }
}
