<?php

namespace App\Http\Controllers;

use App\Mail\TicketConfirm;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventDetail;
use App\Models\EventImage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Storage;

function createEventImage($event_id, $image, $type = 0)
{
    $imageName = '' . request()->name . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

    $image->storeAs('public/images', $imageName);

    return EventImage::create([
        'event_id' => $event_id,
        'image' => $imageName,
        'main' => $type
    ]);
}

class EventController extends Controller
{
    public static function store(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|between:3,50',
            'description' => 'required|string|between:3,1000',
            'host' => 'required|string|min:3',
            'category' => 'required|string|in:Music,Sports,Classical,Lifestyle,Casual,Other',
            'location' => 'required|string|between:3,50',
            'price.*' => 'required|min:1',
            'ticket_count.*' => 'required',
            'date' => 'required|date|after:today',
        ]);

        if($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'The payload is not formatted correctly',
                'errors' => $validation->errors()
            ], 422);
        }

        $existing_event = Event::where('name', $request->name)
            ->where('host', $request->host)
            ->whereHas('info', function ($query) use ($request) {
                $query->where('location', $request->location);
            })
            ->first();

        if($existing_event){
            return response()->json([
                'status' => 422,
                'error' => 'Event already exists',
            ], 422);
        }

        $existingEvent = Event::where('name', $request->name)->where('host', $request->host)->first();

        if($existingEvent){
            $event = $existingEvent;
        }else{
            $event = Event::create([
                'name' => $request->name,
                'description' => $request->description,
                'host' => $request->host,
                'category' => $request->category
            ]);
        }

        $event_details = EventDetail::create([
            'location' => $request->location,
            'event' => $event->id,
            'date' => $request->date
        ]);

        foreach($request->price as $key => $value){
            EventTicket::create([
                'event_detail' => $event_details->id,
                'price' => $value,
                'ticket_count' => $request->ticket_count[$key]
            ]);
        }

        return response()->json([
            'status' => 201,
            'message' => $event->id,
        ], 201);
    }

    public static function images(Request $request, $event_id){
        $validation = Validator::make($request->all(), [
            'main_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10000',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10000',
        ]);

        if($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'The payload is not formatted correctly',
                'errors' => $validation->errors()
            ], 422);
        }

        createEventImage($event_id, $request->main_image, 1);

        if($request->images != ""){
            if(count($request->images) > 0){
                foreach($request->file('images') as $image){
                    createEventImage($event_id, $image);
                }
            }
        }

        return response()->json([
            'status' => 200,
            'events' => 'Images added'
        ]);
    }

    public static function events(){
        return response()->json([
            'status' => 200,
            'events' => EventDetail::with('event_main', 'images', 'tickets')->get()
        ]);
    }

    public static function event($event_id){
        return response()->json([
            'status' => 200,
            'event' => EventDetail::with('event_main', 'images', 'tickets', 'comment')
                ->where('id', $event_id)
                ->first()
        ]);
    }

    public static function test(Request $request){
        return response()->json([
            'status' => 200,
            'events' => 'none'
        ]);
    }
}
