@extends('backend.admin-master')
@section('site-title')
    WhatsApp Button Text Settings
@endsection
@section('style')
    <style>
      textarea{
          width: 300px;
          height:460px;
      }
      img{
          width: 300px;
          height:460px;
      }
    </style>
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>Set WhatsApp Button Text</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.whatsapp.button-text.setting.update') }}" method="POST">
                    @csrf
                    @php
                        $textEvents = [
                            'service_search' => 'Search Service',
                            'view_recent_orders' => 'View Recent Orders',
                            'talk_to_support' => 'Talk to Support',
                            'select_service' => 'Select Service',
                            'included_excluded' => 'Included/Excluded',
                            'show_faqs' => 'Show FAQs',
                            'order_now' => 'Order Now',
                            'select_addons' => 'Select Add-ons',
                            'select_addons_quantity' => 'Select Add-ons Quantity',
                            'select_staff' => 'Select Staff',
                            'select_location' => 'Select Location',
                            'select_slot' => 'Select Slot',
                            'order_service_details' => 'Order Service Details',
                            'order_other_details' => 'Order Other Details',
                            'confirm_order' => 'Confirm Order',
                            'cancel_order' => 'Cancel Order',
                            'agree_to_cancel_order' => 'Agree to Cancel Order',
                            'disagree_to_cancel_order' => 'Disagree to Cancel Order',



                        ];
                    @endphp
                    <div class="row">
                        <div class="col-9">
                            <div class="nav nav-tabs flex-column">
                                @foreach($textEvents as $key => $label)
                                    <div class="nav-item {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#a{{ $key }}">
                                        <div class="form-group mt-4">
                                            <label for="message_{{ $key }}">{{__("WhatsApp Button Text for {$label}")}}</label>
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
                                    @foreach($textEvents as $key => $label)
                                        <div class="tab-pane fade form-group mt-2 {{ $loop->first ? 'active show' : '' }} text-center" id="a{{ $key }}" role="tabpanel">
                                            <label class="d-block font-weight-bold">{{__('Example Preview')}}</label>
                                            <img src="{{ asset("assets/backend/img/whatsapp-preview/{$key}.png") }}" alt="Preview for {{ $label }}" class="img-fluid border rounded shadow-sm mb-2">
                                            <p class="text-muted small">{{__('This is how it will appear in WhatsApp')}}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Save All Texts</button>
                </form>
            </div>
        </div>
    </div>
@endsection

