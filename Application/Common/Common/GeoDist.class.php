<?php
/**
 * a geo-dist wrapper for LBS app
 *
 * @author wangXi <iwisunny@gmail.com>
 * Date: 2016/1/26 16:58
 */
namespace Common\Common;

if (!class_exists('SphinxClient', true)) {
    //first step should install sphinx search engine on server
    require('sphinxapi.php');
}
use \SphinxClient;

class GeoDist
{
    private static $ins = null;

    //sphinx client obj
    protected $spClient=null;

    //最小的搜索范围
    protected $minRange;

    //最大搜索范围, 10km
    protected $maxRange;

    //距离的换算单位
    protected $distUnit;

    //距离换算成 m 的倍数
    protected $multiplier;

    public static function getInstance($opt=array('host'=> 'localhost', 'port'=>9312))
    {
        if (!isset(self::$ins) || !(self::$ins instanceof \SphinxClient)) {
            self::$ins = new self($opt);
        }

        return self::$ins;
    }

    private function __construct($opt=array())
    {
        //delegate sphinx client
        try{
            $this->spClient = new SphinxClient();
            $this->spClient->SetServer($opt['host'], $opt['port']);
            $this->spClient->SetMatchMode(SPH_MATCH_ALL);
//            $this->spClient->SetMatchMode(SPH_MATCH_ANY);
            $this->spClient->SetMaxQueryTime(3);
            $this->spClient->SetArrayResult(true);

            //todo sphinx retrieve COUNT default=20
            $this->spClient-$this->setLimit(0,1000);

            $this->setDistUnit();
            $this->setMinRange(0.0);
            $this->setMaxRange(1000);
        }
        catch(\Exception $e){
            file_put_contents('/tmp/sphinx.log', 'construct:'.$e->getMessage().PHP_EOL, FILE_APPEND);
        }

    }

    public function getMinRange(){
        return $this->minRange;
    }

    public function setMinRange($min){
        $this->minRange = floatval($min* $this->multiplier);
    }

    public function getMaxRange($real_range=false){
        if($real_range){
            return  floatval($this->maxRange / $this->multiplier). $this->distUnit;
        }

        return $this->maxRange;
    }

    public function setMaxRange($max){
        $this->maxRange = floatval($max* $this->multiplier);
    }

    public function getDistUnit()
    {
        return $this->distUnit;
    }

    public function setDistUnit($unit='km'){
        $accepted_unit = array('km', 'm', 'miles', 'kilometers');
        if(!in_array($unit, $accepted_unit)){
            $unit = 'km';
        }

        switch($unit)
        {
            case 'km':
            case 'kilometers':
                $this->multiplier = 1000;
            break;

            case 'm':
                $this->multiplier = 1;
            break;

            case 'miles':
                $this->multiplier=1609.3440;
            break;

            default:
                $this->multiplier=1000;
            break;
        }

        $this->distUnit = $unit;
    }


    public function setLimit($offset, $limit){
        $offset = intval($offset);
        $limit = intval($limit);
        $this->spClient->SetLimits($offset, $limit);

    }
    /**
     * calculate geo-distance
     *
     * @param        $lat_radians
     * @param        $lng_radians
     * @return bool
     */
    public function calc($lat_radians, $lng_radians)
    {
        try{
            $this->spClient->SetFilterFloatRange('@geodist', $this->minRange, $this->maxRange);

            //test client's geo-position
            //武汉光谷软件园: (0.532018, 1.996877)
            //浙江省宁波市余姚市阳明西路28号宁波银行: (0.5245735419855377, 2.1146451105065345)
            $this->spClient->SetGeoAnchor('lat_rad', 'long_rad', floatval($lat_radians), floatval($lng_radians));

            // Sort by the distance from our target
            //TODO: if set "@geodist DESC" will raise error
            $this->spClient->SetSortMode(SPH_SORT_EXTENDED, "@geodist ASC");

            //our sphinx index named unibox_kiosk
            $res=$this->spClient->Query('', 'unibox_kiosk');

            if(!$res){
                file_put_contents('/tmp/sphinx.log', 'calc:'.$this->spClient->GetLastError().PHP_EOL, FILE_APPEND);
            }

            return $res;
        }
        catch(\Exception $e){
            file_put_contents('/tmp/sphinx.log', $e->getMessage().PHP_EOL, FILE_APPEND);
        }

    }
}
