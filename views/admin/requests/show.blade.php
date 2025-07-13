<!-- In resources/views/admin/requests/show.blade.php -->
@extends('admin.layouts.app')

@section('content')
    <h1>Details for Maintenance Request #{{ $maintenanceRequest->id }}</h1>

    <div class="card">
        <div class="card-header">Client & Contract Information</div>
        <div class="card-body">
            <!-- Accessing the User (Client) data -->
            <p><strong>Client Name:</strong> {{ $maintenanceRequest->user->name }}</p>
            <p><strong>Client Iqama:</strong> {{ $maintenanceRequest->user->iqama }}</p>
            
            <!-- Accessing the Contract data -->
            <p><strong>Contract:</strong> {{ $maintenanceRequest->contract->contract_name }}</p>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Vehicle & Representative Information</div>
        <div class="card-body">
            <!-- Accessing the Request's own data -->
            <p><strong>Vehicle Number:</strong> {{ $maintenanceRequest->vehicle_number }}</p>
            <p><strong>Vehicle Model:</strong> {{ $maintenanceRequest->vehicle_model }}</p>

            <!-- Accessing the Representative's data -->
            @if($maintenanceRequest->representative)
                <p><strong>Assigned Representative:</strong> {{ $maintenanceRequest->representative->name }}</p>
                <p><strong>Representative Phone:</strong> {{ $maintenanceRequest->representative->phone }}</p>
            @else
                <p><strong>Assigned Representative:</strong> Not yet assigned.</p>
            @endif
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Products & Services Used</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product/Service</th>
                        <th>Specifications</th>
                        <th>Quantity</th>
                        <th>Price at Time of Order</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- HERE IS THE MAGIC: Looping through the connected products -->
                    @forelse($maintenanceRequest->products as $product)
                        <tr>
                            <td>{{ $product->item_description }}</td>
                            <td>{{ $product->specifications }}</td>
                            
                            <!-- Accessing data from the pivot table -->
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ number_format($product->pivot->price_at_order, 2) }} SAR</td>
                            
                            <td>{{ number_format($product->pivot->quantity * $product->pivot->price_at_order, 2) }} SAR</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No products were attached to this request.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Cost:</th>
                        <th>
                            {{-- We can calculate the total cost easily --}}
                            @php
                                $totalCost = $maintenanceRequest->products->sum(function($product) {
                                    return $product->pivot->quantity * $product->pivot->price_at_order;
                                });
                            @endphp
                            {{ number_format($totalCost, 2) }} SAR
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection