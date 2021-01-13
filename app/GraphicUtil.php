<?php

class GraphicUtil
{
    public static function generate2DPointFrom($attribute)
    {
        return array(
            "geometry" => array(
                "type" => "point",
                "longitude" => $attribute['x'],
                "latitude" => $attribute['y']
            ),
            "symbol" => array(
                "type" => "picture-marker",  // autocasts as new PictureMarkerSymbol()
                "url" => "http://localhost:8888/Project_GIS3D_Final/from.png",
                "width" =>  "32px",
                "height" => "32px"
            ),
            "attributes" => array(
                "name" => $attribute['name'],
                "address" => $attribute['address'],
                "description" => $attribute['description']
            ),
            "popupTemplate" => array(
                "title" => "{name}",
                "content" => "<table class='esri-widget__table'><tr><th>Name</th><td>{name}</td></tr><tr><th>Address</th><td>{address}</td><tr><th>Descrtiption<td>{description}</td></tr></table>"
            )
        );
    }

    public static function generate2DPointTo($attribute)
    {
        return array(
            "geometry" => array(
                "type" => "point",
                "longitude" => $attribute['x'],
                "latitude" => $attribute['y']
            ),
            "symbol" => array(
                "type" => "picture-marker",  // autocasts as new PictureMarkerSymbol()
                "url" => "http://localhost:8888/Project_GIS3D_Final/to.png",
                "width" =>  "32px",
                "height" => "32px"
            ),
            "attributes" => array(
                "name" => $attribute['name'],
                "address" => $attribute['address'],
                "description" => $attribute['description']
            ),
            "popupTemplate" => array(
                "title" => "{name}",
                "content" => "<table class='esri-widget__table'><tr><th>Name</th><td>{name}</td></tr><tr><th>Address</th><td>{address}</td><tr><th>Descrtiption<td>{description}</td></tr></table>"
            )
        );
    }

    public static function generate2DLine($attribute, $exitedLine = null)
    {
        if (!empty($exitedLine)) {
            $exitedLine['geometry']['paths'][] = array($attribute['x'], $attribute['y']);
            return $exitedLine;
        }
        return array (
            "geometry" => array (
                "type"=>"polyline",
                "paths"=> array (
                    array($attribute['x'], $attribute['y'])
                )
            ),
            "symbol" => array(
                "type" => "simple-line",
                "color" => [0, 0, 255],
                "width" => 1
            ),
            
        );
    }

    public static function generate2DLineEvent($attribute, $exitedLine = null)
    {
        if (!empty($exitedLine)) {
            $exitedLine['geometry']['paths'][] = array($attribute['x'], $attribute['y']);
            return $exitedLine;
        }
        return array (
            "geometry" => array (
                "type"=>"polyline",
                "paths"=> array (
                    array($attribute['x'], $attribute['y'])
                )
            ),
            "symbol" => array(
                "type" => "simple-line",
                "color" => [255, 0, 0],
                "width" => 1
            ),
            "attributes" => array(
                "name" => $attribute['name'],
                "description" => $attribute['description']
            ),
            "popupTemplate" => array(
                "title" => "Line {name}",
                "content" => "<table class='esri-widget__table'><tr><th>Name</th><th>Description</th></tr><tr><td>{name}</td><td>{description}</td></tr></table>"
            )
        );
    }
}