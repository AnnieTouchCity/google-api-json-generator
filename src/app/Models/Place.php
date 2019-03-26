<?php
/**
 * Created by PhpStorm.
 * User: Annie
 * Date: 2019/3/15
 * Time: 下午 05:06
 */


namespace Models;



use Illuminate\Database\Eloquent\Model;

/**
 * @property  place_id
 */
class Place extends Model
{
    protected $table = 'places';
    protected $fillable = ['name','place_id','lat','lng','type','address'];



}