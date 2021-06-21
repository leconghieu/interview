<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MainTableController extends Controller
{
    const EDIT_ACTION         = 'edit';
    const EDIT_VALIDATE_RULES = [
        'id'        => 'required|exists:customers',
        'name'      => 'required',
        'email'     => 'required|email',
        'birthdate' => 'required|date'
    ];

    public function edit(Request $request): JsonResponse
    {
        if ($request->ajax() && $request->action === self::EDIT_ACTION) {
            $data      = $request->only('id' ,'name', 'email', 'birthdate');
            $validator = Validator::make($data, self::EDIT_VALIDATE_RULES);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);
            }

            Customer::find($data['id'])->update([
                'name'      =>  $data['name'],
                'email'     =>  $data['email'],
                'birthdate' =>  $data['birthdate']
            ]);

            return response()->json(['message' => 'Success']);    
        }

        return response()->json(['message' => 'Bad request'], Response::HTTP_BAD_REQUEST);
    }

    public function massEdit(Request $request)
    {
        $customers              = $request->customers;
        $customersUpdateFail    = [];
        $customersUpdateSuccess = [];
        
        foreach ($customers as $customer) {
            $validator = Validator::make($customer, self::EDIT_VALIDATE_RULES);

            if ($validator->fails()) {
                $customersUpdateFail[] = [
                    'error'    => $validator->messages(),
                    'customer' => $customer,
                ];

                continue;
            }

            $customersUpdateSuccess[] = [
                'customer' => $customer
            ];

            Customer::find($customer['id'])->update([
                'name'      => $customer['name'],
                'email'     => $customer['email'],
                'birthdate' => $customer['birthdate'],
            ]);
        }

        return response()->json([
            'total_customers'          => count($customers),
            'customers_update_fail'    => $customersUpdateFail,
            'customers_update_success' => $customersUpdateSuccess
        ], Response::HTTP_OK);

    }

    public function search(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            if ($request->query('get_all') && (bool) $request->query('get_all') === true) {
                $customers = Customer::all();
            } else if ($request->query('key')) {
                $key       = $request->query('key');
                $customers = Customer::where('name', 'like', '%' . $key . '%')->orWhere('email', 'like', '%' . $key . '%')->get();
            } else {
                $customers = [];
            }
            
            return response()->json(['customers' => $customers]);
        }

        return response()->json(['message' => 'Bad request'], Response::HTTP_BAD_REQUEST);
    }
}
