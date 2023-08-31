<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\Shipment;
use App\Models\ShipmentApprove;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShipmentController extends Controller
{
    public function store(Request $request)
    {
        $manager = new Manager();
        $Model = $manager->role == 3
            ? Shipment::class : ShipmentApprove::class;
        $request->shipment_date;
        $shipment_date = Carbon::createFromFormat('Y-m-d', $request->shipment_date);
        $current_time = Carbon::now();
        $compare = $shipment_date->gt($current_time);

        if($compare == true)
        {
            $shipment = $Model::query()->create([
                'I\O' => $request->INorOut,
                'SourceAddress_id'=> $request->SourceAddress_id,
                'shipping_company'=> $request->shipping_company,
                'DestinationAddress_id'=> $request->DestinationAddress_id,
                'shipment_date'=>$shipment_date,
                'shipment_type'=>$request->shipment_type,
                'max_quantity'=>$request->max_quantity,
                'shipment_quantity'=> 0,
                'shipment_cost'=>0.0,
                'shipProducts_cost'=>0.0,
                'arrived'=>0,
            ]);
    
            return response()->json([
                'data'=>$shipment
            ], 201);
        }
        else
        {
            return response()->json([
                'message'=>'please change the shipment date'
            ], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    public function showShipments()
    {
        $shipments = Shipment::with([
            'SourceAddresses.regions.cities.countries',
            'DestinationAddresses.regions.cities.countries'
        ])->get();

        $shipmentList = $shipments->map(function ($shipment) {
            $sourceAddress = optional($shipment->SourceAddresses->first());
            $destinationAddress = optional($shipment->DestinationAddresses->first());

            return [
                'id' => $shipment->id,
                'shipping_company' => $shipment->shipping_company,
                'in_or_out' => $shipment->{'I\O'},
                'shipment_date' => $shipment->shipment_date,
                'shipment_day' => date('l', strtotime($shipment->shipment_date)),
                'shipment_type' => $shipment->shipment_type,
                'max_quantity' => $shipment->max_quantity,
                'shipment_quantity' => $shipment->shipment_quantity,
                'shipProducts_cost' => $shipment->shipProducts_cost,
                'shipment_cost' => $shipment->shipment_cost,
                'arrived' => $shipment->arrived,
                'source_address' => $sourceAddress->address,
                'source_city' => optional($sourceAddress->regions->first())->cities->first()->city,
                'source_region' => optional($sourceAddress->regions->first())->region,
                'source_country' => optional($sourceAddress->regions->first())->cities->first()->countries->first()->country,
                'destination_address' => $destinationAddress->address,
                'destination_city' => optional($destinationAddress->regions->first())->cities->first()->city,
                'destination_region' => optional($destinationAddress->regions->first())->region,
                'destination_country' => optional($destinationAddress->regions->first())->cities->first()->countries->first()->country,
            ];
        });

        return $shipmentList->isNotEmpty()
            ? response()->json($shipmentList, 200)
            : response()->json(['message' => 'No shipments to show'], 204);
    }

    public function ShipmentDetails($id)
    {
        $shipment = Shipment::with('SourceAddresses.regions.cities.countries','DestinationAddresses.regions.cities.countries')->where('id', $id)->first();
        $shipmentList[] = [
            'id' => $shipment->id,
            "shipping_company" => $shipment->shipping_company,
            'in_or_out' => $shipment->{'I\O'},
            'shipment_date'=>$shipment->shipment_date,
            'shipment_day' => date('l', strtotime($shipment->shipment_date)),
            'shipment_type'=>$shipment->shipment_type,
            'max_quantity'=>$shipment->max_quantity,
            'shipment_quantity'=>$shipment->shipment_quantity,
            'shipProducts_cost'=>$shipment->shipProducts_cost,
            'shipment_cost'=>$shipment->shipment_cost,
            'arrived'=>$shipment->arrived,
            'source_address' => $shipment->SourceAddresses->first()->address,
            'source_city' => $shipment->SourceAddresses->first()->regions->first()->cities->first()->city,
            'source_region'=> $shipment->SourceAddresses->first()->regions->first()->region,
            'source_country' => $shipment->SourceAddresses->first()->regions->first()->cities->first()->countries->first()->country,
            'destination_address' => $shipment->DestinationAddresses->first()->address,
            'destination_city' => $shipment->DestinationAddresses->first()->regions->first()->cities->first()->city,
            'destination_region'=> $shipment->DestinationAddresses->first()->regions->first()->region,
            'destination_country' => $shipment->DestinationAddresses->first()->regions->first()->cities->first()->countries->first()->country,          
        ];
            return response()->json(
                $shipmentList
            ,200); 
    }
    
    public function editShipment(Request $request, int $id): JsonResponse
    {
        $shipment = Shipment::with('SourceAddresses.regions.cities.countries', 'DestinationAddresses.regions.cities.countries')->find($id);

        if (!$shipment) {
            return response()->json([
                'error' => 'Shipment not found'
            ], 204);
        }

        $validatedData = $request->validate([
            'shipment_type' => 'nullable|string',
            'shipping_company' => 'nullable|string',
            'I\\O' => 'nullable',
            'shipment_date' => 'nullable|date',
            'max_quantity' => 'nullable|integer',
            'shipment_quantity' => 'nullable|integer',
            'shipProducts_cost' => 'nullable',
            'shipment_cost' => 'nullable',
            'arrived' => 'nullable|boolean',
            'DestinationAddress_id'=>'nullable',
            'SourceAddress_id' => 'nullable'
        ]);

        $shipment->fill($validatedData);
        $shipment->save();

        return response()->json([
            'message' => 'Shipment updated successfully'
        ], 200);
    }

    public function shipmentArrive($shipmentId)
    {
        Shipment::find($shipmentId)->update(['arrived' => 1]);
        return response()->json('shipment arrived', 200);
    }
    
}
