<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function validateRestaurant(Request $request, $required=[], $check_unique_id=false): Array
    {
        if(count($request->all())===0){
            return ['status'=>'error','msg'=>'bad request: no data'];
        }

        $validator_options = [
            'id'=>($check_unique_id?'unique:App\Models\Restaurant|':'').'string|size:36',
            'rating'=>'integer|min:0|max:4',
            'name'=>'string|max:255',
            'site'=>'url',
            'email'=>'email',
            'phone'=>'nullable|string',
            'street'=>'nullable|string',
            'city'=>'nullable|string',
            'state'=>'nullable|string',
            'lat'=>'numeric|min:-90|max:90',
            'lng'=>'numeric|min:-180|max:180',
        ];
        array_map(function($k) use (&$validator_options){
            $validator_options[$k] = 'required|'.$validator_options[$k];
        },$required);

        $validator = Validator::make($request->all(),$validator_options);

        if($validator->fails()){
            return ['status'=>'error','msg'=>'bad request','errors'=>$validator->errors()];
        }

        return ['status'=>'ok'];
    }
    /**
     * Restaurant index
     *
     * @OA\Get(
     *      path="/restaurants/",
     *      operationId="getReataurantsList",
     *      tags={"Restaurants"},
     *      summary="Get list of restaurants",
     *      description="Returns list of restaurants",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Restaurant")
     *          )
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      )
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([Restaurant::get()],200);
    }

    /**
     * Read existing restaurant
     *
     * @OA\Get(
     *     path="/restaurants/{restaurant_id}",
     *     tags={"Restaurants"},
     *     operationId="readRestaurant",
     *     @OA\Parameter(
     *         name="restaurant_id",
     *         description="Restaurant id (string32)",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         example="6b7cd221-7859-4ece-8ed6-fd79283f0e65"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant found",
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     )
     * )
     */
    public function readRestaurant($restaurant_id)
    {
        if(!$restaurant=Restaurant::find($restaurant_id)){
            return response()->json(['msg'=>'Not found'],404);
        }
        return response()->json(Restaurant::find($restaurant_id),200);
    }

    /**
     * Add a new restaurant to the store
     *
     * @OA\Post(
     *     path="/restaurants/",
     *     tags={"Restaurants"},
     *     operationId="storeRestaurant",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid input"
     *     )
     * )
     */
    public function storeRestaurant(Request $request): JsonResponse
    {
        $validate = $this->validateRestaurant($request,['rating','name','site','email'],true);

        if($validate['status']==='error'){
            return response()->json($validate, 404);
        }

        //$restaurant = Restaurant::create($request->all());
        $restaurant = new Restaurant($request->all());

        $restaurant_id = $request->id??$this->generateRestaurantId("");
        $restaurant['id'] = $restaurant_id;
        $restaurant->save();

        return response()->json($restaurant,201)->header('Location',url('/api/restaurants/'.$restaurant_id));
    }

    private function generateRestaurantId($id)
    {
        if(strlen($id)===36){
            return $id;
        }
        $chars = 'abcdef';
        $rand = rand(0,15);
        $id.=$rand<10?strval($rand):$chars[$rand-10];

        switch(strlen($id)){
            case 8:
            case 13:
            case 18:
            case 23:
                $id .= '-';
                break;
        }
        return $this->generateRestaurantId($id);
    }

    /**
     * Edit existing restaurant
     *
     * @OA\Put(
     *     path="/restaurants/",
     *     tags={"Restaurants"},
     *     operationId="editRestaurant",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant edited",
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     )
     * )
     */
    public function editRestaurant(Request $request): JsonResponse
    {
        $validate = $this->validateRestaurant($request,['id']);

        if(count($request->all())===0||$validate['status']==='error'){
            return response()->json($validate, 400);
        }

        $restaurant = Restaurant::find($request->id);

        if(!$restaurant){
            return response()->json(['msg'=>'restaurant not found'],404);
        }

        foreach ($request->all() as $k => $v){
            $restaurant[$k] = $v;
        }

        $restaurant->save();

        return response()->json($restaurant,200);
    }

    /**
     * Edit existing restaurant
     *
     * @OA\Delete(
     *     path="/restaurants/{restaurant_id}",
     *     tags={"Restaurants"},
     *     operationId="deleteRestaurant",
     *     @OA\Parameter(
     *         name="restaurant_id",
     *         description="Restaurant id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant deleted"
     *     )
     * )
     */
    public function deleteRestaurant(Request $request, $restaurant_id): JsonResponse
    {
        if(!$restaurant=Restaurant::find($restaurant_id)){
            return response()->json(['msg'=>'not foundsss'],404);
        }
        $restaurant->delete($restaurant_id);
        return response()->json(['msg'=>'deleted'],200);
    }

    /**
     * Read existing restaurant
     *
     * @OA\Get(
     *     path="/restaurants/statistics?latitude={latitude}&longitude={longitude}&radius={radius}",
     *     tags={"Restaurants/Statistics"},
     *     operationId="readRestaurant",
     *     @OA\Parameter(
     *         name="latitude",
     *         description="latitude",
     *         required=true,
     *         in="query",
     *         example=19.436070591035
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         description="longitude",
     *         required=true,
     *         in="query",
     *         example=-99.129786573199
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         description="radius",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         example=200
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant Statistics within radius",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="count",
     *                  type="integer",
     *                  example="6"
     *              ),
     *              @OA\Property(
     *                  property="avg",
     *                  type="float",
     *                  example="2.5"
     *              ),
     *              @OA\Property(
     *                  property="std",
     *                  type="float",
     *                  example="1.5"
     *              )
     *          )
     *     )
     * )
     */
    public function getRestaurantsWithinRadius(Request $request)
    {
        $lat1 = floatval($request->latitude);
        $lng1 = floatval($request->longitude);
        $radius = intval($request->radius);

        $restaurants = Restaurant::select('id','rating','lat','lng')->get();

        $in_radius = collect([]);

        foreach($restaurants as $restaurant){
            $lat2 = floatval($restaurant['lat']);
            $lng2 = floatval($restaurant['lng']);

            $d = $this->calculateDistance($lat1, $lng1, $lat2, $lng2);

            if($d<=$radius) $in_radius->add($restaurant);
        }

        return [
            'count'=>count($in_radius),
            'avg'=>$in_radius->avg('rating'),
            'std'=>$this->std_deviation($in_radius->pluck('rating')->toArray())
        ];
    }

    private function std_deviation($my_arr): float
    {
        $no_element = count($my_arr);
        $var = 0.0;
        $avg = array_sum($my_arr)/$no_element;
        foreach($my_arr as $i)
        {
            $var += pow(($i - $avg), 2);
        }
        return (float)sqrt($var/$no_element);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371e3;
        $theta1 = $lat1 * pi()/180;
        $theta2 = $lat2 * pi()/180;
        $delta_theta = ($lat2 - $lat1) * pi()/180;
        $delta_lambda = ($lng2 - $lng1) * pi()/180;

        $a = pow(sin($delta_theta/2),2) +
            cos($theta1) * cos($theta2) *
            pow(sin($delta_lambda/2),2);
        $c = 2 * atan2(sqrt($a),sqrt(1-$a));

        return $R*$c;
    }
}
