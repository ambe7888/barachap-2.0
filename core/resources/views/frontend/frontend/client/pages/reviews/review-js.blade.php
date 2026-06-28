<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            // show review details in modal
            $(document).on('click','.review_details',function(){
                let review_id = $(this).data('review_id');
                let first_name = $(this).data('first_name');
                let last_name = $(this).data('last_name');
                let full_name = first_name +" "+ last_name;
                let title = $(this).data('title');
                let type = $(this).data('type');
                let rating = $(this).data('rating');
                let message = $(this).data('message');


                $('#review_details .full_name').text(full_name);
                $('#review_details .title').text(title);
                $('#review_details .type').text(type);
                $('#review_details .rating').text(rating);
                $('#review_details .message').text(message);

            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });
            function countries(page){
                $.ajax({
                    url:"{{ route('client.reviews.pagination').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // // search state
            // $(document).on('keyup','#string_search',function(){
            //     let string_search = $(this).val();
            //     $.ajax({
            //         url:"",
            //         method:'GET',
            //         data:{string_search:string_search},
            //         success:function(res){
            //             if(res.status=='nothing'){
            //                 $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
            //             }else{
            //                 $('.search_result').html(res);
            //             }
            //         }
            //     });
            // });

        });
    }(jQuery));

    // toastr success
    function toastr_success_js(msg){
        Command: toastr["success"](msg, "Success !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

    // toastr warning
    function toastr_warning_js(msg){
        Command: toastr["warning"](msg, "Warning !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
</script>
