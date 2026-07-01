@extends('backend.admin-master')
@section('site-title')
WhatsApp Message Settings
@endsection
@section('style')
    <style>
        textarea {
            width: 300px;
            height: 460px;
        }
        img{
            width: 300px;
            height:460px;
        }
    </style>
@endsection
@section('content')
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4>Set WhatsApp Messages for Different Events</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.whatsapp.message.setting.update') }}" method="POST">
                @csrf
                @php
                $messageEvents = [
                    'order_complete' => 'Order Completed',
                    'help_message' => 'Help Message',
                    'search_service' => 'Search Service',
                    'not_available_slots' => 'Not Available Slots',
                    'service_not_found' => 'Service Not Found',
                    'cancel_confirmation' => 'Cancel Confirmation',
                    'not_found_recent_order' => 'Not Found Recent Order',
                    'ask_user_location' => 'Ask User Location',
                    'ask_service_select' => 'Ask Service Select',
                    'ask_addon_select' => 'Ask Addon Select',
                    'ask_select_addon_quantity' => 'Ask Select Addon Quantity',
                    'ask_select_staff' => 'Ask Select Staff',
                    'ask_select_location' => 'Ask Select Location',
                    'ask_select_slot' => 'Ask Select Slot',
                    'ask_provide_date' => 'Ask Provide Date',



                ];
                @endphp

                <div class="row">
                    <div class="col-9">
                        <div class="nav nav-tabs flex-column">
                            @foreach($messageEvents as $key => $label)
                                <div class="nav-item {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#a{{ $key }}">
                                    <div class="form-group mt-4">
                                        <label for="message_{{ $key }}">{{__("WhatsApp Message for {$label}")}}</label>
                                        <textarea name="messages[{{ $key }}]" id="message_{{ $key }}" class="form-control mt-3" rows="10" placeholder="Write the message for {{ $label }}">{{ old("messages.$key", $messages[$key] ?? '') }}</textarea>
                                    </div>
                                    @error("messages.$key")
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="position-sticky top-0">
                            <div class="tab-content">
                                <h5>{{__('Preview Image')}}</h5>
                                @foreach($messageEvents as $key => $label)
                                    <div class="tab-pane fade form-group mt-2 {{ $loop->first ? 'active show' : '' }} text-center" id="a{{ $key }}" role="tabpanel">
                                        <label class="d-block font-weight-bold">{{__('Example Preview')}}</label>
                                        <img src="{{ asset("assets/backend/img/whatsapp-preview/{$key}.png") }}" alt="Preview for {{ $label }}" class="img-fluid border rounded shadow-sm mb-2" >
                                        <p class="text-muted small">{{__('This is how it will appear in WhatsApp')}}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-3">Save All Messages</button>
            </form>
        </div>
    </div>
</div>
@endsection
