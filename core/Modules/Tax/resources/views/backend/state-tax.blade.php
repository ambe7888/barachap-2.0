@extends('backend.admin-master')
@section('site-title')
    {{ __('State Tax') }}
@endsection
@section('style')
    <x-datatable.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-validation.error/>
                <div class="dashboard__card p-4">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All States Tax') }}</h4>
                        <div class="dashboard__card__header__right">
                            <div class="btn-wrapper mt-3">
                                <a href="#1" data-bs-toggle="modal" data-bs-target="#country_tax_new_modal"
                                    class="cmnBtn btn_5 radius-5  btn_bg_blue ">{{ __('Add new state tax') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table_wrapper custom_dataTable">
                            <table class="dataTablesExample">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_states_tax as $tax)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($tax->state)->state }}</td>
                                            <td>{{ $tax->tax_rate }}%</td>
                                            <td>
                                                <x-popup.delete-popup :url="route('admin.tax.state.delete', $tax->id)"/>
                                                <a href="#1" data-bs-toggle="modal"
                                                    data-bs-target="#state_tax_edit_modal"
                                                    class="btn btn-sm btn-primary btn-xs mb-2 me-1 country_tax_edit_btn"
                                                    data-id="{{ $tax->id }}"
                                                     data-state_id="{{ $tax->state_id }}"
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
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update State Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>

                    <form action="{{ route('admin.tax.state.update') }}" method="post">
                        <input type="hidden" name="id" id="country_tax_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_state_id">{{ __('State') }}</label>
                                <select name="state_id" class="form-control" id="edit_state_id">
                                    @foreach ($all_states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state }}</option>
                                    @endforeach
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
                            <button type="submit" class="cmnBtn btn_5 radius-5  btn_bg_blue btn-sm">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="country_tax_new_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New State Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.state.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="state_id">{{ __('State') }}</label>
                                <select name="state_id" class="form-control" id="state_id">
                                    @foreach ($all_states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tax_rate">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                            <button type="submit" class="cmnBtn btn_5 radius-5  btn_bg_blue mt-4 pr-4 pl-4">{{ __('Add New') }}</button>
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
            $(document).on('click', '.country_tax_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let state_id = el.data('state_id');
                let tax_rate = el.data('tax_rate');
                let modal = $('#state_tax_edit_modal');

                // make select option
                $("#state_tax_edit_modal select option[value=" + state_id + "]").attr("selected",
                    "true");
                $("#state_tax_edit_modal .list li[data-value=" + state_id + "]").trigger("click");
                $("#state_tax_edit_modal .modal-footer").trigger("click");
                modal.find('#country_tax_id').val(id);
                modal.find('#edit_state_id').val(state_id);
                modal.find('#edit_tax_rate').val(tax_rate);
            });
        });
    </script>
@endsection
