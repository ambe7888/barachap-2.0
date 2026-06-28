<!-- State Edit Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit City') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.city.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="city_id" id="city_id" value="">
                <div class="modal-body">
                    <x-form.text :title="__('City')" :type="__('text')" :name="'edit_city'" :id="'edit_city'" :placeholder="__('Enter city name')"/>
                    <div class="single-input">
                        <label class="label-title mt-3">{{ __('Select States') }}</label>
                        <select name="edit_state" id="edit_state" class="form__control radius-5 country_select22">
                            @foreach($all_states as $data)
                                <option value="{{ $data->id }}">{{ $data->state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.timezone :title="__('Select Timezone')" :name="'edit_timezone'" :id="'edit_timezone'" :class="'form-control timezone_select2_edit'"  />
                </div>
                <div class="modal-footer">
                    <button type="button" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 edit_state">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
