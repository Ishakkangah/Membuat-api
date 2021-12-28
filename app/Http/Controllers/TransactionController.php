<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class TransactionController extends Controller
{
    public function index()
    {
        $transaction = transaction::orderBy('time', 'DESC')->get();
        $response = [
            'message' => 'List transaction order by time',
            'data' => $transaction,
        ];

        return response()->json($response, Response::HTTP_OK);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:expense,revenue',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $transaction = transaction::create($request->all());
            $response = [
                'message' => 'The data was inserted',
                'data' => $transaction,
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Fails ' . $e->errorInfo,
            ]);
        }
    }

    public function show($id)
    {
        $transaction = transaction::findOrFail($id);
        $response = [
            'message' => 'Detail of transactions resource',
            'data' => $transaction,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $transaction = transaction::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:expense,revenue',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $transaction->update($request->all());
            $response = [
                'message' => 'The data was updated',
                'data' => $transaction,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Fails ' . $e->errorInfo,
            ]);
        }
 
    }

    public function destroy($id)
    {
        $transaction = transaction::findOrFail($id);
        try {
            $transaction->delete();
            $response = [
                'message' => 'The data was deleted',
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Fails ' . $e->errorInfo,
            ]);
        }
    }
}
