@extends('backend.admin-master')
@section('site-title', __('Tax settings'))
@section('content')
    <div class="dashboard__card">
        <x-validation.error/>
        <div class="dashboard__card__header p-4">
            <h3 class="dashboard__card__title">{{ __('Tax settings') }}</h3>
        </div>
        <div class="dashboard__card__body custom__form mt-4 p-4">
            <form action="{{ route('admin.tax.settings') }}" method="post" class="row">
                @csrf

                @method('PUT')
                <div class="col-xxl-6">
                    <div class="form-group row">
                        <label for="tax_system" class="col-md-4">{{ __('Tax system') }}
                            <span id="enable-info-about-tax-system"> <i class="las la-info-circle"></i></span>
                        </label>
                    </div>

                    <div class="col-md-12 p-0 m-0 mt-3" id="advance_tax_system_settings">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select id="tax_inclusive_exclusive" name="tax_inclusive_exclusive" class="form-control">
                                    <option {{ get_static_option('tax_inclusive_exclusive') == 'inclusive' ? 'selected' : '' }} value="inclusive"> {{ __('Inclusive tax') }} </option>
                                    <option {{ get_static_option('tax_inclusive_exclusive') == 'exclusive' ? 'selected' : '' }} value="exclusive"> {{ __('Exclusive tax') }} </option>
                                </select>
                            </div>
                        </div>

                        <div class="country_tax_section mt-4">
                            <label for="tax_round_at_subtotal" class="col-md-4">{{ __('Country Tax') }}</label>
                            <div class="col-md-4">
                                <label for="tax_rate_by_country" class="form-check-label">
                                    <input name="tax_rate_by_country" id="tax_rate_by_country" type="number" class="form-control" value="{{get_static_option('tax_rate_by_country')  }}" placeholder="{{ __('0.00') }}">
                                </label>
                            </div>
                                <span class="text-danger">{{ __('Notes: Enter the tax rate for each country if using a country-specific tax system. Make sure to select the exclusive option for proper tax calculation.') }}</span>
                        </div>

                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" class="cmnBtn btn_5 radius-5  btn_bg_blue">{{ __('Update Tax Settings') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Function to toggle visibility based on selected option
        function toggleCountryTaxInput() {
            var selectedValue = $("#tax_inclusive_exclusive").val();
            if (selectedValue === 'exclusive') {
                $(".country_tax_section").show();
            } else {
                $(".country_tax_section").hide();
            }
        }

        // Initial check on page load
        toggleCountryTaxInput();

        // Check on change event
        $(document).on("change", "#tax_inclusive_exclusive", function() {
            toggleCountryTaxInput();
        });
    </script>
@endsection
