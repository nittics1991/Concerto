<?php

/**
 *   ImageOperation
 *
 * @version 191007
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use InvalidArgumentException;
use RuntimeException;

class ImageOperation
{
    /**
     *   image
     *
     * @var resource
     */
    protected $image;
    
    /**
     *   __construct
     *
     * @param resource $image
     **/
    public function __construct($image)
    {
        if (
            !is_resource($image)
            || (get_resource_type($image) != 'gd')
        ) {
            throw new InvalidArgumentException("resource type not matche");
        }
        $this->image = $image;
    }
    
    /**
     *   createFromFile
     *
     * @param  string $file
     * @return $this
     **/
    public static function createFromFile($file)
    {
        $image = imagecreatefrompng($file);
        
        if ($image === false) {
            throw new RuntimeException(
                "unable to create image from filepath"
            );
        }
        return new self($image);
    }
    
    /**
     *   image合成
     *
     * @param  string $file
     * @param  int    $x
     * @param  int    $y
     * @return $this
     **/
    public function merge($file, $x = 20, $y = 0)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException("image file not found");
        }
        $src = $this->image;
        $dest = imagecreatefrompng($file);
        
        if ($dest === false) {
            throw new RuntimeException(
                "unable to create image from filepath"
            );
        }
        
        $w = imagesx($this->image);
        $h = imagesy($this->image);
        
        imagecopy($src, $dest, $x, $y, 0, 0, $w, $h);
        imagedestroy($dest);
        return $this;
    }
    
    /**
     *   output
     *
     * @param string $file
     **/
    public function output($file)
    {
        imagepng($this->image, $file);
    }
}
