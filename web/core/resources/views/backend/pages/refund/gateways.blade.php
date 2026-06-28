@extends('backend.admin-master')
@section('title', __('Refund Gateway Settings'))
@section('style')
    <x-datatable.css/>
    <style>
        .w-90 {width: 90%;}
        .w-20 {width: 20%;}

        .dashboard__card {
             height: 100%!important;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-7">
                <x-notice.general-notice
                        :description="__('Notice: Users can select any of the below-listed payment methods for refund, those are active.')"
                />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Refund Payment Gateways') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <div class="table_wrapper custom_dataTable">
                                <table class="dataTablesExample">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Gateway Name') }}</th>
                                            <th>{{ __('Gateway Filed') }}</th>
                                            <th>{{ __('Gateway Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gateways as $gateway)
                                            <tr>
                                                <td>{{ $gateway->id }}</td>
                                                <td>{{ ucfirst($gateway->name) }}</td>
                                                <td>{{ implode(' , ', unserialize($gateway->field)) }}</td>
                                                <td>
                                                    <x-status.table.gateway-status :status="$gateway->status" />
                                                    @can('refund-payment-status-change')
                                                        <span><x-status.status-change :url="route('admin.refund.gateway.status', $gateway->id)"/></span>
                                                    @endcan
                                                </td>
                                                <td>
                                                    @can('refund-payment-gateway-edit')
                                                        <a class="mb-1 cmnBtn btn_5 btn_bg_warning radius-5 btnIcon edit_gateway_modal update-gateway"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#edit-gateway-modal"
                                                           data-name="{{ ucfirst($gateway->name) }}"
                                                           data-id="{{ $gateway->id }}"
                                                           data-blog-filed="{{ json_encode(unserialize($gateway->field)) }}">
                                                            {{ __('Edit Gateway') }}
                                                        </a>
                                                    @endcan
                                                    @can('refund-payment-gateway-delete')
                                                    <x-popup.delete-popup :url="route('admin.refund.gateway.delete',$gateway->id)"/>
                                                    @endcan
                                                   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="dashboard__card sideWarapper__item bg__white padding-20 radius-10">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Payment Gateway Create') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form customMarkup__single">
                            <form method="POST" action="{{ route('admin.refund.gateway.create') }}">
                                @csrf
                                <div class="single-input mb-3">
                                    <label class="label-title"> {{ __('Payment Gateway Name') }}</label>
                                    <input class="form-control" name="name" placeholder="{{ __("Write gateway name...") }}">
                                </div>

                                <div class="dashboard__card__header">
                                    <h4 class="dashboard__card__title">{{ __('Gateway required filed.') }}</h4>
                                </div>
                                <div class="dashboard__card__body mb-5">
                                    <div class="form-group row">
                                        <div class="w-90 d-flex align-items-center">
                                            <input class="form-control" name="field[]" placeholder="{{ __('Write filed name...') }}">
                                        </div>
                                        <div
                                            class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                                            <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn_wrapper mt-3">
                                    <button class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Create Gateway') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="edit-gateway-modal" tabindex="-1" aria-labelledby="edit-gateway-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="" method="POST" action="{{ route('admin.refund.gateway.update') }}">
                    @csrf
                    <input type="hidden" value="" name="id" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __("Update refund gateway") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Payment Gateway Name') }}</label>
                            <input class="form-control" name="name" placeholder="{{ __("Write gateway name...") }}">
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="title">{{ __('Gateway required filed.') }}</h4>
                            </div>
                            <div class="card-body gateway-filed-body">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <div class="btn_wrapper">
                            <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        // change status
        $(document).on('click','.swal_status_change',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure to change status complete? Once you done you can not revert this !!")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('Yes, change it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });

        $(document).on("click", ".update-gateway", function() {
            let fileds = JSON.parse($(this).attr("data-blog-filed"));
            $("#edit-gateway-modal input[name='name']").val($(this).attr("data-name"))
            $("#edit-gateway-modal input[name='id']").val($(this).attr("data-id"))

            if (fileds.length > 0) {
                let list_filed = "";

                fileds.forEach(function(value, index, array) {
                    list_filed += `
                        <div class="form-group row mb-5">
                            <div class="w-90 d-flex align-items-center">
                                <input class="form-control" value="${value}" name="field[]" placeholder="Write filed name...">
                            </div>
                            <div class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                                <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    `;
                })

                $(".gateway-filed-body").html(list_filed);
            } else {
                $(".gateway-filed-body").html(`<div class="form-group row mb-5">
                    <div class="w-90 d-flex align-items-center">
                        <input class="form-control" name="field[]" placeholder="Write filed name...">
                    </div>
                    <div class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                        <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>`);
            }
        });

        $(document).on("click", ".gateway-filed-add", function() {
            let elem = $(this).parent().parent();
            elem.parent().append(elem.clone());
        });

        $(document).on("click", ".gateway-filed-remove", function() {
            if ($(".gateway-filed-remove").length == 1) {
                return null;
            }
            let elem = $(this).parent().parent().fadeOut(250, () => {
                $(this).parent().parent().remove();
            });
        });

    </script>
@endsection
