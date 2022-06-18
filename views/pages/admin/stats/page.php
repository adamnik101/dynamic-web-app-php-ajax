<?php


class page
{
    private $name;
    private $visits24 = 0;
    private $visitsAllTime = 0;

    //region Constructor
    public function __construct($name){
        $this->name = $name;
    }
    //endregion



    //region NAME
    public function GetName(){
        return $this->name;
    }
    //endregion
    public function GetVisits24(){
        return $this->visits24;
    }
    public function GetVisitsAllTime(){
        return $this->visitsAllTime;
    }
    //region VISITS
    public function increaseVisits24($visit){
        $this->visits24 += $visit;
    }
    public function increaseVisitsAllTime($visit){
        $this->visitsAllTime += $visit;
    }
    //endregion
}