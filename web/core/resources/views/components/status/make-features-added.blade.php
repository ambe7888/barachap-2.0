<a tabindex="0" class="cmnBtn btn_5 btn_bg_warning btnIcon radius-5 swal_status_change m-0"
   title="{{ __('Remove Featured') }}"
   data-bs-toggle="tooltip"
   data-bs-placement="left"
>
    <i class="las la-star"></i>
</a>
<form method='post' action='{{$url}}' class="d-none">
<input type='hidden' name='_token' value='{{csrf_token()}}'>
<br>
<button type="submit" class="swal_form_submit_btn d-none"></button>
 </form>
