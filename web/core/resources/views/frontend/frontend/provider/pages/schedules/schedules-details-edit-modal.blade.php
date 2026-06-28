<!-- State Edit Modal -->
<div class="modal fade" id="userDetailsEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Schedule Info') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('provider.schedule.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="edit_schedule_details">
                    <div class="edit_user_detailsInfo">
                        <span class="email_send_message d-none"></span>
                        <input type="hidden" name="edit_id" id="edit_id" value="">
                        <x-form.text :title="__('Day')" :type="'text'" :name="'edit_day'"
                                     :id="'edit_day'" :placeholder="__('Enter day')"/>
                        <x-form.text :title="__('Last Name')" :type="'text'" :name="'edit_schedule'"
                                     :id="'edit_schedule'" :placeholder="__('Enter schedule')"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cmnBtn btn_5 btn_bg_danger radius-5"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 update_schedule_info">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
