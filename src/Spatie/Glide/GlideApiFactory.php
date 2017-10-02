<?php

namespace Spatie\Glide;

use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManager;
use League\Glide\Api\Api;
use League\Glide\Manipulators\Blur;
use League\Glide\Manipulators\Brightness;
use League\Glide\Manipulators\Contrast;
use League\Glide\Manipulators\Filter;
use League\Glide\Manipulators\Gamma;
use League\Glide\Manipulators\Orientation;
use League\Glide\Manipulators\Output;
use League\Glide\Manipulators\Pixelate;
use League\Glide\Manipulators\Rectangle;
use League\Glide\Manipulators\Sharpen;
use League\Glide\Manipulators\Size;
use League\Glide\Manipulators\Watermark;

class GlideApiFactory
{
    public static function create()
    {
        // Set image manager
        $imageManager = new ImageManager([
            'driver' => self::getDriver()
        ]);

        //Set watermark folder
        $watermarks = new League\Flysystem\Filesystem(
            new League\Flysystem\Adapter\Local(Config::get('laravel-glide.watermark'))
        );

        // Set manipulators
        $manipulators = [
            new Orientation(),
            new Rectangle(),
            new Size(Config::get('laravel-glide.maxSize')),
            new Brightness(),
            new Contrast(),
            new Gamma(),
            new Sharpen(),
            new Filter(),
            new Blur(),
            new Pixelate(),
            new Output(),
            new Watermark($watermarks),
        ];

        // Set API
        return new Api($imageManager, $manipulators);
    }

    /**
     * @return string
     */
    public static function getDriver()
    {
        $driver = Config::get('laravel-glide.driver');

        if (! $driver) {
            $driver = 'gd';
        }

        return $driver;
    }
}
