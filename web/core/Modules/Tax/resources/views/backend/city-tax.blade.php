@extends('backend.admin-master')
@section('site-title')
    {{ __('City Tax') }}
@endsection
@section('style')
    <x-datatable.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12 mt-2">
                <x-validation.error/>
                <div class="card p-3">
                    <div class="card-body">
                        <h4 class="header-title mt-3">{{ __('All Cities Tax') }}</h4>
                        <div class="btn-wrapper mt-3 mb-4">
                            <a href="#1" data-bs-toggle="modal" data-bs-target="#state_tax_new_modal"
                               class="cmnBtn btn_5 radius-5  btn_bg_blue ">{{ __('Add new city tax') }}
                            </a>
                        </div>
                        <div class="table_wrapper custom_dataTable">
                            <table class="dataTablesExample">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_city_tax as $tax)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($tax->city)->city }}</td>
                                            <td>{{ float_amount_with_currency_symbol($tax->tax_rate) }}</td>
                                            <td>
                                                <x-popup.delete-popup :url="route('admin.tax.city.delete', $tax->id)"/>
                                                <a href="#1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#state_tax_edit_modal"
                                                    class="btn btn-primary btn-sm btn-xs mb-2 me-1 state_tax_edit_btn"
                                                    data-id="{{ $tax->id }}"
                                                    data-state_id="{{ $tax->state_id }}"
                                                    data-city_id="{{ $tax->city_id }}"
                                                    data-tax_rate="{{ $tax->tax_rate }}">
                                                    <i class="ti-pencil"></i>
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
        </div>
    </div>


        <div class="modal fade" id="state_tax_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update City Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.city.update') }}" method="post">
                        <input type="hidden" name="id" id="state_tax_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group country-wrapper">
                                <label for="state_id">{{ __('State') }}</label>
                                <select name="state_id" class="form-control" id="edit_state_id">
                                    <option value="">{{ __('Select State') }}</option>
                                    @foreach ($all_states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group state-wrapper">
                                <label for="edit_city_id">{{ __('city') }}</label>
                                <select name="city_id" class="form-control" id="edit_city_id">
                                    <option value="">{{ __('select city') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_tax_rate">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="edit_tax_rate" name="tax_rate"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="state_tax_new_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New City Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.city.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="state_id">{{ __('State') }}</label>
                                <select name="state_id" class="form-control" id="create_state_id">
                                    <option value="">{{ __('Select State') }}</option>
                                    @foreach ($all_states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group create-state-wrapper">
                                <label for="city_id">{{ __('State') }}</label>
                                <select name="city_id" class="form-control" id="create_city_id">
                                    <option value="">{{ __('Select State first') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tax_rate">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{ __('Add New') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection
@section('scripts')
  <x-datatable.js/>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.state_tax_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let state_id = el.data('state_id');
                let city_id = el.data('city_id');
                let tax_rate = el.data('tax_rate');

                let modal = $('#state_tax_edit_modal');
                //ajax call to get country related state and set select the current value
                $.get('{{ route('admin.city.by.state') }}', {
                    id: el.data('state_id')
                }).then(function(data) {
                    $('#edit_city_id').html('');
                    let option = "";
                    let list = "";
                    for (const city of data) {
                        let selected = city.id == city_id ? 'selected' : '';
                        option += `<option value="` + city.id + `">` + city.city + `</option>`;
                        list += `<li data-value="` + city.id + `" class="option">` + city.city +
                            `</li>`;
                    }

                    $('#edit_city_id').html(option);
                    $(".state-wrapper .list").html(list);
                    $(".state-wrapper .list li[data-value=" + city_id + "]").trigger("click");
                    modal.find('.modal-footer').trigger("click");
                });

                modal.find('#state_tax_id').val(id);
                modal.find('#edit_city_id').val(city_id);
                modal.find('#edit_state_id option[value="' + el.data('state_id') + '"]').attr(
                    'selected', true);

                $("#state_id option[value=" + state_id + "]").select();
                $(".country-wrapper .list li[data-value=" + state_id + "]").trigger("click");
                $('#edit_city_id option[value=' + city_id + ']').attr("selected", "true");
                modal.find('#edit_tax_rate').val(tax_rate);
                modal.find('.modal-footer').trigger("click");
            });

            $('#state_id').on('change', function() {
                let id = $(this).val();
                $.get('{{ route('admin.city.by.state') }}', {
                    id: id
                }).then(function(data) {
                    $('#city_id').html('');
                    for (const city of data) {
                        $('#city_id').append('<option value="' + city.id + '">' + city.city +
                            '</option>');
                    }
                });
            });

            $('#create_state_id').on('change', function() {
                let id = $(this).val();
                $.get('{{ route('admin.city.by.state') }}', {
                    id: id
                }).then(function(data) {
                    $('#create_city_id').html('');
                    let option = "";
                    let list = "";
                    for (const city of data) {
                        option += '<option value="' + city.id + '">' + city.city + '</option>';
                        list += `<li data-value="` + city.id + `" class="option">` + city.city +
                            `</li>`;
                    }

                    $('#create_city_id').html(option);
                    $(".create-state-wrapper .list").html(list);
                });
            });

            $('#edit_state_id').on('change', function() {
                let id = $(this).val();
                $.get('{{ route('admin.city.by.state') }}', {
                    id: id
                }).then(function(data) {
                    $('#edit_city_id').html('');
                    let ed_option = "";
                    let ed_list = `<li data-value="" class="option">Select State</li>`;

                    for (const city of data) {
                        ed_option += '<option value="' + city.id + '">' + city.city + '</option>';
                        ed_list += `<li data-value="` + city.id + `" class="option">` + city.city + `</li>`;
                    }

                    $('#edit_city_id').html(ed_option);
                    $(".state-wrapper .list").html(ed_list);
                });
            });
        });
    </script>
@endsection
