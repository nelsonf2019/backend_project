<?php

namespace App\Http\Controllers;

//use App\Contact;
use App\Mail\SendContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
/**
* @OA\Info(title="API Contact", version="1.0")
*
* @OA\Server(url="http://localhost:8000")
*/

class ContactController extends Controller
{
     /**
     * @OA\Post(
     *     path="/api/Contact/store",
     *     summary="Guardar datos de contacto",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 ),
     *                 example={"name": "betox", "email": "alberto.urbaez@25watts.com.ar","phone": "3435411789", "message":"Test de envio de email"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function store(Request $request)
    {

        try {
            $contact          = new Contact;
            $contact->name    = $request->name;
            $contact->email   = $request->email;
            $contact->phone   = $request->phone;
            $contact->message = $request->message;
            try {
                Mail::to($request->email)->send(new SendContact($contact));
                $contact->send_email = "se envio el email";
            } catch (\exception $e) {
                $contact->send_email = "fallo el envio: {$e->getMessage()}";
            }
            $contact->save();
        } catch (\exception $e) {
            return response()->json("Se genero un error: {$e->getMessage()}", 404);
        }
        return response()->json("Mensaje fue enviado con exíto", 201);
    }

      //  Mail::to($request->email)->send(new SendContact());
        //return response()->json("Mensaje fue enviado con exíto {$request->email}", 201);

}
