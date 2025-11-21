<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Client_emails;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class ClientsController extends Controller
{
 
   function clients(Request $request) {
        $clients = Clients::all();
        return response()->json(['clients' =>  $clients], 200);
   }

   function add(Request $request) {
  
        $allData = $request->all();
        $errors = [];
        $errors = $this->validateClients($allData);
        if ($errors) {
            return response()->json(['message' => 'no client added', 'errors' => $errors], 404);
        }
        $client = Clients::create([
           'name' => trim($allData['name']),
           'surname' => trim($allData['surname']),
           'phone' => isset($allData['phone']) ? $allData['phone'] : ""
        ]);
        return response()->json(['message' => ' client added', 'id' => $client->id], 200);             
   }

   function update(Request $request, $id) {
        $client = Clients::find($id);   
        if ($client) {
        
            $allData = $request->all(); 
            $errors = $this->validateClients($allData);
            if ($errors) {
                return response()->json(['message' => 'no client update', 'errors' => $errors], 404);
            } 
            $client->name = trim($allData['name']);
            $client->surname = trim($allData['surname']);
            $client->phone = isset($allData['phone']) ? $allData['phone'] : "";
            $client->save();
            return response()->json(['message' => 'client updated', 'id' => $client->id], 200);
        }
        return response()->json(['message' => 'Client not found'], 404);   
   }

   private function validateClients($data) { 
      $errors = [];
      if (!isset($data["name"])) { 
          $errors[] = "no name given";
      } elseif (strlen(trim($data["name"])) < 3) {
          $errors[] = "name is too short";
      }
      if (!isset($data["surname"])) {
          $errors[] = "no surname given";
      } elseif (strlen(trim($data["surname"])) < 3) {
          $errors[] = "surname is too short";
      }
      return $errors;   
   }
   
   function destroy($id) {
        $client = Clients::find($id);
        if ($client) {
            $client->delete();
            return response()->json(['message' => 'Client deleted'], 200);
        }
        return response()->json(['message' => 'Client not found'], 404);
   }   

   function addemail(Request $request, $id) {
       $client = Clients::find($id);
       $allData = $request->all();  
       if (!isset($allData["email"])) {
           return response()->json(['message' => 'no email provided'], 404);  
       }
       if ($client) {
           if (filter_var($allData['email'], FILTER_VALIDATE_EMAIL)) {
               if ($client->client_emails->contains('email', $allData['email'])) {
                   return response()->json(['message' => 'Email address provided is already added'], 404); 
               } else {
                 Client_emails::create([
                    'client_id' => $id,
                    'email' =>$allData['email'], 
                 ]);
                 return response()->json(['message' => 'Email Added'], 200);
               }
           } else {
                return response()->json(['message' => 'Invalid email'], 404);  
           }
       }
       return response()->json(['message' => 'Client not found'], 404);      
   }

   function removeemail(Request $request, $id) {
       $client = Clients::find($id);
       $allData = $request->all();  
       if (!isset($allData["email"])) {
           return response()->json(['message' => 'no email provided'], 404);  
       }
       if ($client) {
           if (filter_var($allData['email'], FILTER_VALIDATE_EMAIL)) {
               if ($client->client_emails->contains('email', $allData['email'])) {
                    Client_emails::where('client_id', $id)->where("email", $allData['email'])->delete();
                    return response()->json(['message' => 'Email Deleted'], 200);
               } else {
                    return response()->json(['message' => 'no email address provided by the client'], 404);  
               }
           } else {
                return response()->json(['message' => 'Invalid email'], 404);  
           }
       }
       return response()->json(['message' => 'Client not found'], 404);      
   }

   function sendEmail(Request $request,$id) {
       $client = Clients::find($id);
       $allData = $request->all();  
       if (!isset($allData["body"])) {
           return response()->json(['message' => 'no body'], 404);  
       }
       if ($client) {
            $msg = [];
            foreach ($client->client_emails as $email) {
                // Mail::to($user->email)->queue(new WelcomeMail($user)); przy bardziej rozbudowanej produkcji
                Mail::to($email->email)->send(new WelcomeMail($client->name." ".$client->surname, $allData["body"]));
                $msg[] = $email->email;
            }
             return response()->json(['message' => 'Send email to: '.implode(", ", $msg)], 200);
       }
       return response()->json(['message' => 'Client not found'], 404);  

   }

 
}
