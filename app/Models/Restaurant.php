<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     title="Restaurant",
 *     description="Restaurant model",
 *     required={"id","rating","name","site","phone","email","street","city","state","lat","lng"},
 *     @OA\Xml(
 *         name="Restaurant"
 *     )
 * )
 */

class Restaurant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'restaurants';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'rating',
        'name',
        'site',
        'email',
        'phone',
        'street',
        'city',
        'state',
        'lat',
        'lng'
    ];

    protected $hidden = ['deleted_at','created_at','updated_at'];

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="string36",
     *     example="851f799f-0852-439e-b9b2-df92c43e7672"
     * )
     *
     * @var string
     */
    public $id;

    /**
     * @OA\Property(
     *      title="rating",
     *      description="Restaurant rating (int, 0 to 4)",
     *      example=3,
     * )
     *
     * @var integer
     */
    public $rating;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new restaurant",
     *      example="Fisher's"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="site",
     *      description="Restaurant website URL",
     *      example="https://www.fishers.com.mx"
     * )
     *
     * @var string
     */
    public $site;

    /**
     * @OA\Property(
     *      title="email",
     *      description="Restaurant contact email",
     *      example="example@fishers.com.mx"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="phone",
     *      description="Restaurant phone number",
     *      example="5552060416"
     * )
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="street",
     *      description="Restaurant street address",
     *      example="Palmas Hills Mz. II"
     * )
     *
     * @var string
     */
    public $street;

    /**
     * @OA\Property(
     *      title="city",
     *      description="Restaurant city address",
     *      example="Huixquilucan"
     * )
     *
     * @var string
     */
    public $city;

    /**
     * @OA\Property(
     *      title="state",
     *      description="Restaurant state address",
     *      example="Estado de México"
     * )
     *
     * @var string
     */
    public $state;

    /**
     * @OA\Property(
     *      title="lat",
     *      description="Restaurant latitude coordinate (float, -90 to 90)",
     *      example=19.3930193
     * )
     *
     * @var float
     */
    public $lat;

    /**
     * @OA\Property(
     *      title="ln",
     *      description="Restaurant longitude coordinate (float, -180 to 180)",
     *      example=-99.2807104
     * )
     *
     * @var float
     */
    public $lng;
}
