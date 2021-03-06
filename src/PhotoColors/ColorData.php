<?php

namespace PhotoColorNames\PhotoColors;

class ColorData
{
    private $rgb;
    private $hsl;
    private $frequency;
    private $name;

    /**
     * @return mixed
     */
    public function getRgb()
    {
        return $this->rgb;
    }

    /**
     * @param mixed $rgb
     */
    public function setRgb($rgb)
    {
        if (substr($rgb, 0, 1) != '#') $rgb = '#' . $rgb;
        $this->rgb = $rgb;
    }

    /**
     * @return mixed
     */
    public function getHsl()
    {
        return $this->hsl;
    }

    /**
     * @param mixed $hsl
     */
    public function setHsl($hsl)
    {
        $this->hsl = $hsl;
    }

    public function setHslFromParts($hue, $saturation, $lightness){
        $this->setHsl(array(
            'hue' => $hue,
            'saturation' => $saturation,
            'lightness' => $lightness
        ));
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function __construct($color = null, $frequency = null, $name = null)
    {
        if (isset($color['type']) && isset($color['color']) && $color['type'] == 'rgb')
            $this->setRgb($color['color']);
        else
            $this->setHsl($color['color']);

        if(!is_null($frequency)) $this->setFrequency($frequency);
        if(!is_null($name)) $this->setName($name);
    }

    /**
     * Transforms RGB colors from integer values to hexadecimals and adds a # in front of the string
     *
     * @param $red
     * @param $green
     * @param $blue
     */
    public function rgbIntToHex($red, $green, $blue)
    {
        $this->rgb = '#';
        $this->rgb .= base_convert($red, 10, 16) . base_convert($green, 10, 16) . base_convert($blue, 10, 16);
    }

    public function getRed()
    {
        return substr($this->getRgb(), 1, 2);
    }

    public function getRedInt(){
        return ord(pack('H*',$this->getRed()));
    }

    public function getGreen()
    {
        return substr($this->getRgb(), 3, 2);
    }

    public function getGreenInt(){
        return ord(pack('H*',$this->getGreen()));
    }

    public function getBlue()
    {
        return substr($this->getRgb(), 5, 2);
    }

    public function getBlueInt(){
        return ord(pack('H*',$this->getBlue()));
    }

    public function getHue()
    {
        $result = $this->getHsl();
        return $result['hue'];
    }

    public function getSaturation()
    {
        $result = $this->getHsl();
        return $result['saturation'];
    }

    public function getLightness()
    {
        $result = $this->getHsl();
        return $result['lightness'];
    }

    /**
     * Convert RGB to HSL
     *
     * Ported from: http://chir.ag/projects/ntc/
     * Licensed under Creative Commons 2.5: https://creativecommons.org/licenses/by/2.5/
     */
    public function rgbToHsl()
    {
        $rgb = array(
            'red' => ord(pack('H*', $this->getRed())) / (float)255,
            'green' => ord(pack('H*', $this->getGreen())) / (float)255,
            'blue' => ord(pack('H*', $this->getBlue())) / (float)255,
        );

        $min = min($rgb['red'], min($rgb['green'], $rgb['blue']));
        $max = max($rgb['red'], max($rgb['green'], $rgb['blue']));
        $delta = $max - $min;
        $lightness = ($min + $max) / (float)2;

        $saturation = 0;
        if ($lightness > 0 && $lightness < 1) {
            $saturation = $delta / ($lightness < 0.5 ? (2 * $lightness) : (2 - 2 * $lightness));
        }

        $hue = 0;
        if ($delta > 0) {
            if ($max == $rgb['red'] && $max != $rgb['green'])
                $hue += ($rgb['green'] - $rgb['blue']) / $delta;
            if ($max == $rgb['green'] && $max != $rgb['blue'])
                $hue += (2 + ($rgb['blue'] - $rgb['red']) / $delta);
            if ($max == $rgb['blue'] && $max != $rgb['red'])
                $hue + -(4 + ($rgb['red'] - $rgb['green']) / $delta);
            $hue /= 6;
        }

        $this->setHslFromParts($hue, $saturation, $lightness);
    }
}