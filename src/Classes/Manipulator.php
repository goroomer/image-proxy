<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 03/12/16
 * Time: 09:03
 */
class Manipulator
{

    private $params;
    private $image;
    private $output_image;
    public $errors = [];

    public function __construct(Image $image, $params = null)
    {
        $this->image = $image;
        $this->set_params($params);
        $this->manipulate();
    }


    private function set_params($manipulation_params)
    {
        $params = [];
        if (!isset($manipulation_params) || $manipulation_params == '') {
            return false;
        }

        foreach (explode(',', $manipulation_params) as $param) {
            $param = explode(':', $param);
            $key = $param[0];
            $value = $param[1];
            switch ($key) {
                case 'w':
                    if (is_numeric(substr($value, -1))) {
                        $params['width'] = $value;
                        $params['constrain'] = false;
                    } else {
                        $params['width'] = substr($value, 0, -1);
                        $params['constrain'] = true;
                    }
                    break;
                case 'h':
                    if (is_numeric(substr($value, -1))) {
                        $params['height'] = $value;
                        $params['constrain'] = false;
                    } else {
                        $params['height'] = substr($value, 0, -1);
                        $params['constrain'] = true;
                    }
                    break;
                case 'c':
                    $crop_array = explode('x', $value);
                    $params['crop_width'] = $crop_array[0];
                    $params['crop_height'] = $crop_array[1];
                    break;
                case 'fill':
                    $res_array = explode('x', $value);
                    $params['fill_width'] = $res_array[0];
                    $params['fill_height'] = $res_array[1];
                    break;
                case 'fit':
                    $res_array = explode('x', $value);
                    $params['fit_width'] = $res_array[0];
                    $params['fit_height'] = $res_array[1];
                    break;
                case 'q':
                    $params['quality'] = $value;
                    break;
                case 'text':
                    $params['text'] = $value;
                    break;
                case 'text-color':
                    $params['text-color'] = '#' . $value;
                    break;
                case 'text-size':
                    $params['text-size'] = $value;
                    break;
                case 'text-position':
                    $res_array = explode('x', $value);
                    $params['text-x'] = $res_array[0];
                    $params['text-y'] = $res_array[1];
                    break;
                case 'watermark':
                    $params['watermark-url'] = base64_decode($value);
                    $params['watermark-location'] = 'top-left';
                    $params['watermark-x'] = 0;
                    $params['watermark-y'] = 0;
                    break;
                case 'watermark-location':
                    $params['watermark-location'] = $value;
                    break;
                case 'watermark-offset':
                    $res_array = explode('x', $value);
                    $params['watermark-x'] = $res_array[0];
                    $params['watermark-y'] = $res_array[1];
                    break;
                default:
                    break;
            }
        }
        $this->params = $params;
    }

    private function manipulate()
    {

        if (isset($this->params['width']) || isset($this->params['height'])) {
            $width = null;
            $height = null;
            if (isset($this->params['width'])) {
                $width = $this->params['width'];
            }

            if (isset($this->params['height'])) {
                $height = $this->params['height'];
            }
            if ($this->params['constrain']) {
                $this->image->process_image()->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $this->image->process_image()->resize($width, $height);
            }
        }


        if (isset($this->params['crop_width'])) {
            $this->image->process_image()->crop($this->params['crop_width'], $this->params['crop_height']);
        }

        if (isset($this->params['fill_width'])) {
            $this->image->process_image()->fill($this->params['fill_width'], $this->params['fill_height']);
        }

        if (isset($this->params['fit_width'])) {
            $this->image->process_image()->fit($this->params['fit_width'], $this->params['fit_height']);
        }

        if (isset($this->params['watermark-url'])) {
            $this->image->process_image()->insert($this->params['watermark-url'], $this->params['watermark-location'], $this->params['watermark-x'], $this->params['watermark-y']);
        }

        if (isset($this->params['text'])) {
            $this->image->process_image()->text($this->params['text'], $this->params['text-x'], $this->params['text-y'], function ($font) {
                $font->file(getcwd() . '/fonts/arial.ttf');
                $font->color($this->params['text-color']);
                $font->size($this->params['text-size']);
            });
        }

        $quality = isset($this->params['quality']) ? $this->params['quality'] : '100';
        //print_r( $this->params);
        if ($quality != '100') {
            $params['content'] = $this->image->process_image()->response(null, (int)$quality);
        } else {
            $params['content'] = $this->image->process_image()->response();
        }

        $params['mime'] = $this->image->getMime();
        $this->output_image = new Image($params);
        return $this->output_image;
    }

    /**
     * @return mixed
     */
    public function getOutputImage()
    {
        return $this->output_image;
    }
}