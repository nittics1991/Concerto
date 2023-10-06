<?php

/**
 *   ImageOperation
 *
 * @version 230117
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use GdImage;
use InvalidArgumentException;
use RuntimeException;

class ImageOperation
{
    /**
    *   @var GdImage
    */
    protected GdImage $image;

    /**
    *   __construct
    *
    *   @param GdImage $image
    */
    public function __construct(
        GdImage $image
    ) {
        $this->image = $image;
    }

    /**
    *   createFromFile
    *
    *   @param string $file
    *   @return self
    */
    public static function createFromFile(
        string $file
    ): self {
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
    *   @param string $file
    *   @param int $x
    *   @param int $y
    *   @return static
    */
    public function merge(
        string $file,
        int $x = 20,
        int $y = 0
    ): static {
        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                "image file not found"
            );
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
    *   @param string $file
    */
    public function output(
        string $file
    ): void {
        imagepng($this->image, $file);
    }
}
