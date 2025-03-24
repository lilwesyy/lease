<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);
        
        $uploadedFiles = [];
        
        if ($request->hasFile('documents')) {
            foreach($request->file('documents') as $file) {
                $path = $file->store('customer-documents', 'public');
                $document = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'document_type' => 'identity'
                ];
                
                // Store as temporary document or associate with customer
                if ($request->has('customer_id')) {
                    $document['customer_id'] = $request->customer_id;
                    Document::create($document);
                }
                
                $uploadedFiles[] = $document;
            }
        }
        
        return response()->json([
            'success' => true,
            'files' => $uploadedFiles
        ]);
    }

    public function uploadCardDocuments(Request $request)
    {
        $request->validate([
            'cardDocuments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);
        
        $uploadedFiles = [];
        
        if ($request->hasFile('cardDocuments')) {
            foreach($request->file('cardDocuments') as $file) {
                $path = $file->store('customer-card-documents', 'public');
                $document = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'document_type' => 'card'
                ];
                
                // Store as temporary document or associate with customer
                if ($request->has('customer_id')) {
                    $document['customer_id'] = $request->customer_id;
                    Document::create($document);
                }
                
                $uploadedFiles[] = $document;
            }
        }
        
        return response()->json([
            'success' => true,
            'files' => $uploadedFiles
        ]);
    }
    
    // Method to associate temporary documents with a customer
    public function attachToCustomer(Request $request, $customerId)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:documents,id'
        ]);
        
        Document::whereIn('id', $request->document_ids)
                ->update(['customer_id' => $customerId]);
                
        return response()->json([
            'success' => true,
            'message' => 'Documents attached to customer successfully'
        ]);
    }
    
    // Method to get all documents for a customer
    public function getCustomerDocuments($customerId)
    {
        $identityDocuments = Document::where('customer_id', $customerId)
                                     ->where('document_type', 'identity')
                                     ->get();
                                     
        $cardDocuments = Document::where('customer_id', $customerId)
                                 ->where('document_type', 'card')
                                 ->get();
                                 
        return response()->json([
            'success' => true,
            'identity_documents' => $identityDocuments,
            'card_documents' => $cardDocuments
        ]);
    }
    
    // Method to delete a document
    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete the file from storage
        if (Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }
        
        $document->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }
}