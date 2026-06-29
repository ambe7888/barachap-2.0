@extends('backend.admin-master')
@section('site-title')
    {{__('All Provider Orders')}}
@endsection
@section('style')
    <style>
        .custom_table tr td:not(:first-child) {
            min-width: 42px;
        }
        .dataTablesExample {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            margin-top: 15px !important;
            border: 1px solid #f0f0f0 !important;
            background: #ffffff !important;
            border-radius: 8px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02) !important;
        }
        .dataTablesExample thead {
            background-color: #198754 !important; /* Theme Clean Green */
            color: #ffffff !important;
        }
        .dataTablesExample thead th {
            padding: 14px 16px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 0.5px !important;
            border: none !important;
        }
        .dataTablesExample tbody tr {
            border-bottom: 1px solid #f5f5f5 !important;
            transition: all 0.2s ease-in-out !important;
        }
        .dataTablesExample tbody tr:hover {
            background-color: #f8fdf9 !important;
        }
        .dataTablesExample tbody td {
            padding: 14px 16px !important;
            vertical-align: middle !important;
            font-size: 13px !important;
            color: #444444 !important;
            border: none !important;
        }
        .table_customer__title {
            font-size: 12px !important;
            margin-bottom: 4px !important;
            color: #666666 !important;
            font-weight: 500 !important;
        }
        .table_customer__title strong {
            color: #198754 !important;
        }
        .status_btn {
            padding: 4px 10px !important;
            border-radius: 12px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            display: inline-block !important;
            letter-spacing: 0.3px !important;
        }
        .status_btn.in_progress {
            background-color: #e8f5e9 !important;
            color: #2e7d32 !important;
        }
        .status_btn.completed {
            background-color: #e3f2fd !important;
            color: #1565c0 !important;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('All Provider Orders') }}</h4>
                       </div>
                   </div>
                 </div>
                <x-validation.error/>
                <div class="tableStyle_three mt-4">
                    @include('backend.pages.orders.user-order-filter')
                    <div class="table_wrapper custom_Table">
                        <div class="search_result">
                            @include('backend.pages.orders.user-orders.search-order')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Status Modal -->
    <div class="modal fade" id="OrderStatusChangeModal" tabindex="-1" role="dialog"
         aria-labelledby="editModal"
         aria-hidden="true">
        <form action="{{ route('admin.user.order.status.change') }}" method="post">
            @csrf
            <input type="hidden" name="id" class="order_id">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModal">{{ __('Change Order Status') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status_id">{{ __('Select Status') }}</label>
                            <select name="status_id" id="status_id" class="form-control">
                                <option value="">{{ __('Select Status') }}</option>
                                <option value="0">{{ __('Pending') }}</option>
                                <option value="1">{{ __('Accepted') }}</option>
                                <option value="2">{{ __('Completed') }}</option>
                                <option value="4">{{ __('Cancel') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('backend.pages.orders.manual-payment-file-modal')
@endsection
@section('scripts')
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){

                $(document).on('click', '.open-modal', function(event) {
                    // Get file URL and name from data attributes
                    var fileUrl = $(this).data('file-url');
                    var fileName = $(this).data('file-name');

                    // Get modal elements
                    var filePreview = $('#filePreview');
                    var fileDownload = $('#fileDownload');

                    // Reset preview and download elements
                    filePreview.hide().attr('src', '');
                    fileDownload.hide().attr('href', '');

                    // Check file type and update modal content
                    var fileExtension = fileUrl.split('.').pop().toLowerCase();
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        // Image file
                        filePreview.attr('src', fileUrl).show();
                        fileDownload.hide();
                    } else {
                        // Non-image file
                        filePreview.hide();
                        fileDownload.attr('href', fileUrl).show().text('Download ' + fileName);
                    }

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById('fileModal'));
                    modal.show();
                });

                //order status change
                $(document).on('click', '.order_status_change_modal', function () {
                    let el = $(this);
                    let order_id = el.data('order_id');
                    let form = $('#OrderStatusChangeModal');
                    form.find('.order_id').val(order_id);
                });

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

                // live search
                $(document).on('keyup','.string_search',function(){
                    let string_search = $(this).val();
                    $.ajax({
                        url:"{{ route('admin.user.order.search') }}",
                        method:'GET',
                        data:{string_search:string_search},
                        success:function(res){
                            if(res.status=='nothing'){
                                $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                            }else{
                                $('.search_result').html(res);
                            }
                        }
                    });
                });

                // pagination
                $(document).on('click', '.pagination li a', function(e){
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];

                    // Get the currently active tab status from the URL
                    let activeTab = $('.tabs li.active a').attr('href');
                    let status = new URL(activeTab).searchParams.get('status');

                    user_orders(page, status);
                });
                function user_orders(page, status){
                    $.ajax({
                        url:"{{ route('admin.user.order.paginate').'?page='}}" + page + "&status=" + status,
                        success:function(res){
                            $('.search_result').html(res);
                        }
                    });
                }

            });
        })(jQuery);
    </script>
@endsection
