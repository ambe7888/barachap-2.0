<!-- State Edit Modal -->
<div class="modal fade" id="userDetailsEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit User Info') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.user.info.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="edit_user_details">
                    <div class="edit_user_detailsInfo">
                        <span class="email_send_message d-none"></span>
                        <input type="hidden" name="edit_user_id" id="edit_user_id" value="">
                        <x-form.text :title="__('First Name')" :type="'text'" :name="'edit_first_name'"
                                     :id="'edit_first_name'" :placeholder="__('Enter first name')"/>
                        <x-form.text :title="__('Last Name')" :type="'text'" :name="'edit_last_name'"
                                     :id="'edit_last_name'" :placeholder="__('Enter last name')"/>
                        <x-form.text :title="__('User Name')" :type="'text'" :name="'edit_username'"
                                     :id="'edit_username'" :placeholder="__('Enter last name')"/>
                        <span id="user_name_availability"></span>
                        <x-form.text :title="__('Email')" :type="'text'" :name="'edit_email'" :id="'edit_email'"
                                     :placeholder="__('Enter email')"/>
                        <span id="email_availability"></span>

                        <x-form.text :title="__('Phone')" :type="'text'" :name="'edit_phone'" :id="'edit_phone'"
                                     :placeholder="__('Enter phone number')"/>
                        <span id="phone_availability"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cmnBtn btn_5 btn_bg_danger radius-5"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit"
                            class="cmnBtn btn_5 btn_bg_blue radius-5 update_user_info">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
