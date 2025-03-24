<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        $clients = Customer::with(['identityDocuments', 'cardDocuments'])->get();
        return response()->json($clients);
    }

    public function createCustomer(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'birthDate' => 'required|date',
            'language' => 'required|string|max:2',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'licenseNumber' => 'required|string|max:50',
            'licenseValidUntil' => 'required|date',
            'nationalID' => 'required|string|max:50',
            'identityCardValidUntil' => 'required|date',
            'cardNumber' => 'required|string|max:20',
            'expirationDate' => 'required|string|max:7',
            'cvv' => 'required|string|max:4',
            'cardHolder' => 'required|string|max:255',
        ]);

        $customer = Customer::create($validatedData);

        return response()->json(['message' => 'Customer created successfully', 'customer' => $customer], 201);
    }

    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json(['message' => 'Customer deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete customer'], 500);
        }
    }

    public function editCustomer(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->update($request->all());

            return response()->json(['message' => 'Customer updated successfully', 'customer' => $customer], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update customer', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePrivacy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->privacy = true;
            $customer->save();

            return response()->json(['message' => 'Privacy updated successfully', 'customer' => $customer], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update privacy', 'error' => $e->getMessage()], 500);
        }
    }
}
