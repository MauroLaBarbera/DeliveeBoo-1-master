<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Restaurant;
use App\Plate;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class RestaurantController extends Controller
{
    public function index(){

        $restaurants = DB::table('cuisine_restaurant')
                ->join('restaurants', 'restaurants.id', '=', 'cuisine_restaurant.restaurant_id')
                ->join('cuisines', 'cuisines.id', '=', 'cuisine_restaurant.cuisine_id')
                ->select('restaurants.id','restaurants.name','restaurants.description','restaurants.image','restaurants.address','restaurants.city','restaurants.cap','restaurants.phone_number')
                ->distinct()
                ->paginate(4);
                //->get();

        $count = DB::table('restaurants')->count();

                return response()->json(['success' => true,
                'results' => $restaurants,'count'=>($count)]);
    }


    public function id($id){
        $restaurants = DB::table('cuisine_restaurant')

                ->join('restaurants', 'restaurants.id', '=', 'cuisine_restaurant.restaurant_id')
                ->join('cuisines', 'cuisines.id', '=', 'cuisine_restaurant.cuisine_id')
                ->select('restaurants.id','restaurants.name','restaurants.description','restaurants.image','restaurants.address','restaurants.city','restaurants.cap','restaurants.phone_number','cuisines.type')
                ->distinct()
                ->where('restaurants.id',$id)
                ->get();


        $tmp = [];
        foreach($restaurants as $val)
        $tmp[] = $val->type;
        $restaurants[0]->type = $tmp;


        return response()->json(['success' => true,
            'results' => $restaurants[0]]);
    }

     public function cuisine($type){

       $str = explode('-', $type);

        $restaurants = DB::table('cuisine_restaurant')
                ->join('restaurants', 'restaurants.id', '=', 'cuisine_restaurant.restaurant_id')

                ->join('cuisines AS cucina', 'cucina.id', '=', 'cuisine_restaurant.cuisine_id')

                ->select('restaurants.*','cucina.type')

                ->whereIn('cucina.type', $str)

                ->groupBy('restaurant_id')

                ->having(DB::raw('count(restaurant_id)'),'=',count($str))

                ->paginate(4);
                //->get();



    foreach ($restaurants as $value)
         $value->type =  implode(' ',$str);

         $count = DB::table('users')->count();

        return response()->json(['success' => true,
            'results' => $restaurants,'count'=>$count]);

    }
}

