<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\User;
use App\Flat;
use Illuminate\Support\Facades\Auth;
use Validator;

use Monolog\Logger;
class PikController extends Controller
{

    public $successStatus = 200;

    public $errorValidationStatus = 420;

    public $errorNotFoundStatus = 404;

    public $paginate = 50;

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request){

	$flat = new Flat;

	$validator = Validator::make($request->post(), $flat->rules);
	if ($validator->fails()) {
	    return response()->json(['error' => 'Bad request params'], $this->errorValidationStatus);
	}
	$flat->fill($request->post());
	$flat->save();

	return response()->json(['success' => 'OK'], $this->successStatus);
    }


    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request){
	$query = [];
	if (!isset($request->post()['query'])){
	    return response()->json(['error' => 'Bad request params'], $this->errorValidationStatus);
	}

	$perPage = $this->paginate;
	if (isset($request->post()['per_page']) && $request->post()['per_page'] <= $this->paginate){
	    $perPage = $request->post()['per_page'];
	}

	$page = 1;
	if (isset($request->post()['page'])){
	    $page = $request->post()['page'];
	}

	foreach ($request->post()['query'] as $key => $value){
	    $query[] = [$key, 'like', "%$value%"];
	}
	Log::info($query); /* should use better logging channel */

	$flats = Flat::where($query)->paginate($perPage, ['*'], 'page', $page);
	if (!$flats){
	    return response()->json(['error' => 'Not found'], $this->errorNotFoundStatus);
	}

	$success = ['flats' => []];
	foreach ($flats as $flat){
	    $success['flats'][] = $flat;
	}
	$success['item_count'] = $flats->total();

	return response()->json(['success' => $success], $this->successStatus);
    }


} 