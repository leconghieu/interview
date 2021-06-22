<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
	public function getListOfCustomer(Request $request): JsonResponse
	{
		if ($request->query('key')) {
            $key       = $request->query('key');
            $customers = Customer::where('name', 'like', '%' . $key . '%')->orWhere('email', 'like', '%' . $key . '%')->get();
        } else {
            $customers = Customer::all();
        }

        return response()->json(['customers' => $customers], JsonResponse::HTTP_OK);
	}
}
