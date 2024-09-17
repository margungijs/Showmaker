<?php

namespace App\Http\Controllers;

use App\Mail\TicketConfirm;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\CardException;
use App\Models\User;
use App\Models\EventDetail;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\File;
use App\Models\Purchases1;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent = PaymentIntent::create([
                'amount' => $request->event_price * 100,
                'currency' => 'eur',
            ]);

            $user = User::where('id', $request->id)->first();

            $name = $user->name;

            return response()->json([
                'client_secret' => $intent->client_secret,
                'name' => $name
            ]);
        } catch (CardException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function test(Request $request)
    {
        $event = EventDetail::where('id', $request->event_id)->with('event_main', 'tickets')->first();

        $event->tickets->where('price', $request->event_price)->each(function ($ticket) {
            $ticket->decrement('ticket_count');
        });

        $event->save();

        $user = User::where('id', $request->id)->first();
        $email = $user->email;

        $data = [
            'title' => 'Purchase Confirmation',
            'content' => [
                'host' => $event['event_main'][0]['host'],
                'title' => $event['event_main'][0]['name'],
                'location' => $event['location'],
                'date' => $event['date'],
                'price' => $request->event_price,
            ],
        ];

        $pdf = app('dompdf.wrapper')->loadView('pdf_template', $data);

        // Ensure the directory exists
        $directory = storage_path('app/temp');
        File::makeDirectory($directory, 0755, true, true);

        // Save the PDF to the directory
        $pdfPath = $directory . '/document.pdf';
        $pdf->save($pdfPath);

        $body = "This is your purchase confirmation email for " . $event['event_main'][0]['host'] . ": " . $event['event_main'][0]['name'];

        Mail::to([$email])->send(new TicketConfirm($body, $pdfPath));

        Purchases1::create([
            'user' => $request->id,
            'event' => $request->event_id,
            'main' => $event['event_main'][0]['id'],
            'price' => $request->event_price
        ]);

        unlink($pdfPath);

        return response()->json([
            'message' => $event
        ]);
    }

    public static function payedEvents(Request $request, $user_id){
        return response()->json([
            'events' => Purchases1::where('user', $user_id)
                ->with('events', 'test', 'image')
                ->get(),
        ]);
    }

}
