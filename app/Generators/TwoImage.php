<?php

namespace App\Generators;

use Closure;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use Tzsk\Collage\Contracts\CollageGenerator;

class TwoImage extends CollageGenerator
{
    /**
     * @var Image
     */
    protected $canvas;

    /**
     * @param  Closure  $closure
     * @return \Intervention\Image\Image
     */
    public function create($closure = null)
    {
        $this->check(2);

        $this->createCanvas();
        $this->makeSelection($closure);

        return ImageManagerStatic::canvas(
            $this->file->getWidth(),
            $this->file->getHeight(),
            $this->file->getColor()
        )->insert($this->canvas, 'center');
    }

    /**
     * Create inner canvas.
     */
    protected function createCanvas()
    {
        $height = $this->file->getHeight() - $this->file->getPadding();
        $width = $this->file->getWidth() - $this->file->getPadding();

        $this->canvas = ImageManagerStatic::canvas($width, $height);
    }

    /**
     * Process Vertical
     */
    public function vertical()
    {
        $this->resizeVerticalImages();

        $this->canvas->insert($this->images->get(0), 'center-left');
        $this->canvas->insert($this->images->get(1), 'center-right');
    }

    /**
     * Process Horizontal.
     */
    public function horizontal()
    {
        $this->resizeHorizontalImages();

        $this->canvas->insert($this->images->get(0));
        $this->canvas->insert($this->images->get(1), 'bottom-left');
    }

    /**
     * @param  Closure  $closure
     */
    protected function makeSelection($closure = null)
    {
        if ($closure) {
            call_user_func($closure, $this);
        } else {
            $this->horizontal();
        }
    }

    /**
     * Resize Images for Horizontal Use.
     */
    protected function resizeHorizontalImages()
    {
        $height = $this->file->getHeight() / 2 - ceil($this->file->getPadding() * 0.75);

        $images = collect();
        foreach ($this->images as $image) {
            $images->push($image->fit($this->file->getWidth() - $this->file->getPadding(), $height));
        }

        $this->images = $images;
    }

    /**
     * Resize Images for Vertical Use.
     */
    protected function resizeVerticalImages()
    {
        $width = 235;
        $images = collect();
        foreach ($this->images as $image) {
            $width = round($width);
            $height = 320;

            $images->push($image->fit($width, $height));
        }

        $this->images = $images;
    }
}
