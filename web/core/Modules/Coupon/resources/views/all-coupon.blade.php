@extends('backend.admin-master')
@section('site-title')
    {{ __(' Coupon') }}
@endsection
@section('style')
    <x-datatable.css />
    <link rel="stylesheet" href="{{ asset('assets/tenant/frontend/css/nice-select.css') }}">
@endsection
@section('content')
 <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="row g-4">
                    <div class="col-xl-7 col-lg-12">
                        <div class="dashboard__card p-3">
                            <div class="dashboard__card__header">
                                <h4 class="dashboard__card__title">{{ __('All  Coupon') }}</h4>
                                <x-bulk-action.bulk-action/>
                            </div>
                            <x-validation.error/>
                            <div class="dashboard__card__body mt-4 p-3">
                                <div class="table-wrap table-responsive custom_table">
                                    <table class="table table-default">
                                        <thead>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Code') }}</th>
                                            <th>{{ __('Discount') }}</th>
                                            <th>{{ __('Expire Date') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($all_coupons as $data)
                                                <tr>
                                                    <td>{{ $data->id }}</td>
                                                    <td>{{ $data->code }}</td>
                                                    <td>
                                                        @if ($data->discount_type == 'percentage')
                                                            {{ $data->discount }}%
                                                        @else
                                                            {{ amount_with_currency_symbol($data->discount) }}
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d M Y', strtotime($data->expire_date)) }}</td>
                                                    <td><x-status.table.active-inactive :status="$data->status"/></td>
                                                    <td>
                                                        <x-popup.delete-popup :title="''" :url="route('admin.coupon.delete',$data->id)"/>
                                                        <a href="#1" data-bs-toggle="modal"
                                                            data-bs-target="#category_edit_modal"
                                                            class="btn btn-sm btn-warning btn-xs mb-2 me-1 category_edit_btn"
                                                            data-id="{{ $data->id }}" data-title="{{ $data->title }}"
                                                            data-code="{{ $data->code }}"
                                                            data-discount="{{ $data->discount }}"
                                                            data-discount_type="{{ $data->discount_type }}"
                                                            data-expire_date="{{ $data->expire_date }}"
                                                            data-status="{{ $data->status }}">
                                                            <i class="las la-pen-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-12">
                        <div class="dashboard__card p-4">
                            <div class="dashboard__card__header">
                                <h4 class="dashboard__card__title">{{ __('Add New Coupon') }}</h4>
                            </div>
                            <div class="dashboard__card__body custom__form mt-4 mb-3">
                                <form action="{{ route('admin.coupon.new') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">{{ __('Coupon Title') }}</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="{{ __('Title') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="code">{{ __('Coupon Code') }}</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            placeholder="{{ __('Code') }}" required>
                                        <span id="status_text" class="text-danger" style="display: none"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount">{{ __('Discount') }}</label>
                                        <input type="number" class="form-control" id="discount" name="discount"
                                            placeholder="{{ __('Discount') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_type">{{ __('Coupon Type') }}</label>
                                        <select name="discount_type" class="form-control" id="discount_type" required>
                                            <option value="percentage">{{ __('Percentage') }}</option>
                                            <option value="amount">{{ __('Amount') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="expire_date">{{ __('Expire Date') }}</label>
                                        <input type="date" class="form-control flatpickr" id="expire_date" name="expire_date"
                                            placeholder="{{ __('Expire Date') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select name="status" class="form-control" id="status" required>
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                    <button type="submit" id="coupon_create_btn"
                                        class="cmnBtn btn_5 radius-5  btn_bg_blue   mt-3">{{ __('Add New Coupon') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 </div>

        <div class="modal fade" id="category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form p-3">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Coupon') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.coupon.update') }}" method="post">
                        <input type="hidden" name="id" id="coupon_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{ __('Coupon Title') }}</label>
                                <input type="text" class="form-control" id="edit_title" name="title"
                                    placeholder="{{ __('Title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_code">{{ __('Coupon Code') }}</label>
                                <input type="text" class="form-control" id="edit_code" name="code"
                                    placeholder="{{ __('Code') }}">
                                <span id="status_text" class="text-danger" style="display: none"></span>
                            </div>
                            <div class="form-group">
                                <label for="edit_discount">{{ __('Discount') }}</label>
                                <input type="number" class="form-control" id="edit_discount" name="discount"
                                    placeholder="{{ __('Discount') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_discount_type">{{ __('Coupon Type') }}</label>
                                <select name="discount_type" class="form-control" id="edit_discount_type">
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                    <option value="amount">{{ __('Amount') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_expire_date">{{ __('Expire Date') }}</label>
                                <input type="date" class="form-control flatpickr" id="edit_expire_date"
                                    name="expire_date" placeholder="{{ __('Expire Date') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="cmnBtn btn_5 radius-5  btn_bg_blue">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            flatpickr(".flatpickr", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $(document).on('click', '.category_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let status = el.data('status');
                let modal = $('#category_edit_modal');
                let discount_on = el.data('discount_on');
                let discount_on_details = el.data('discount_on_details');

                modal.find('#coupon_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_code').val(el.data('code'));
                modal.find('#edit_discount').val(el.data('discount'));
                modal.find('#edit_discount_type').val(el.data('discount_type'));
                modal.find('#edit_expire_date').val(el.data('expire_date'));
                modal.find('#edit_discount_type[value="' + el.data('discount_type') + '"]').attr('selected',
                    true);
                modal.find('#edit_title').val(el.data('title'));
                modal.find('#edit_discount_on').val(el.data('discount_on'));


                $('#edit_form_' + discount_on + ' option[value=' + discount_on_details + ']').attr(
                    'selected', true);
                $('#edit_form_' + discount_on).fadeIn();

            });

            $(document).on('keyup', '#code', function() {
                validateCoupon(this);
            });

            $(document).on('keyup', '#edit_code', function() {
                validateCoupon(this);
            });
        });


        function validateCoupon(context) {
            let code = $(context).val();
            let submit_btn = $(context).closest('form').find('button[type=submit]');
            let status_text = $(context).siblings('#status_text');
            status_text.hide();

            if (code.length) {
                submit_btn.prop("disabled", true);

                $.get("{{ route('admin.coupon.check') }}", {
                    code: code
                }).then(function(data) {
                    if (data > 0) {
                        let msg = "{{ __('This coupon is already taken') }}";
                        status_text.removeClass('text-success').addClass('text-danger').text(msg).show();
                        submit_btn.prop("disabled", true);
                    } else {
                        let msg = "{{ __('This coupon is available') }}";
                        status_text.removeClass('text-danger').addClass('text-success').text(msg).show();
                        submit_btn.prop("disabled", false);
                    }
                });
            }
        }
    </script>
@endsection
