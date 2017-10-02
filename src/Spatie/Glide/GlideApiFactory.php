<?php

namespace Spatie\Glide;

use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManager;
use League\Glide\Api\Api;
use League\Glide\Manipulators\Background;
use League\Glide\Manipulators\Blur;
use League\Glide\Manipulators\Border;
use League\Glide\Manipulators\Brightness;
use League\Glide\Manipulators\Contrast;
use League\Glide\Manipulators\Crop;
use League\Glide\Manipulators\Encode;
use League\Glide\Manipulators\Filter;
use League\Glide\Manipulators\Gamma;
use League\Glide\Manipulators\Orientation;
use League\Glide\Manipulators\Pixelate;
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
        $watermarks = new \League\Flysystem\Filesystem(
            new \League\Flysystem\Adapter\Local(Config::get('laravel-glide.watermark.path', storage_path('app/public')))
        );

        // Set manipulators
        $manipulators = [
            new Orientation(),
            new Crop(),
            new Size(Config::get('laravel-glide.maxSize')),
            new Brightness(),
            new Contrast(),
            new Gamma(),
            new Sharpen(),
            new Filter(),
            new Blur(),
            new Pixelate(),
            new Watermark($watermarks),
            new Background(),
            new Border(),
            new Encode(),
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
