<?php
namespace Common\Common;
class Util{
    /* 二维数组排序
     * @params    arr        数据源
     * @params    key        排序依赖的key
     * @params    sort       排序方式，0：顺序，1：倒序  （默认顺序）
     * @return    mi
    */
    public function multiArraySort($arr, $key, $sort = 0){
        $f = ($sort == 0) ? SORT_ASC : SORT_DESC;
        $k = array();
        foreach ($arr as $value) {
            $k[] = $value[$key];
        }
        array_multisort($k, $f, $arr);
        return $arr;
    }

    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     */
     public function getDistance($lat1, $lng1, $lat2, $lng2){
         $earthRadius = 6367000; //approximate radius of earth in meters

         /*
           Convert these degrees to radians
           to work with the formula
         */

         $lat1 = ($lat1 * pi() ) / 180;
         $lng1 = ($lng1 * pi() ) / 180;

         $lat2 = ($lat2 * pi() ) / 180;
         $lng2 = ($lng2 * pi() ) / 180;

         /*
           Using the
           Haversine formula

           http://en.wikipedia.org/wiki/Haversine_formula

           calculate the distance
         */

         $calcLongitude = $lng2 - $lng1;
         $calcLatitude = $lat2 - $lat1;
         $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
         $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
         $calculatedDistance = $earthRadius * $stepTwo;

         return round($calculatedDistance/1609);//1mile = 1609m
     }
}
