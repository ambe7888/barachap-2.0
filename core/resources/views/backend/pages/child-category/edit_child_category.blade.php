@extends('backend.admin-master')
@section('site-title')
    {{__('Edit Child Category')}}
@endsection
@section('style')
    <x-media.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <style>
        .media-upload-btn-wrapper .img-wrap {
            position: relative;
            display: inline-block;
            max-width: 30%;
        }
        .img-wrap img {
            max-width: 161px;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-6 col-lg-6 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between mb-3">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Edit Child Category')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('admin.child.category')}}">{{__('All Child Categories')}}</a>
                    </div>
                </div>
                <x-validation.error/>
                <form action="{{route('admin.child.category.edit',$child_category->id)}}" method="post" enctype="multipart/form-data" id="edit_category_form">
                    @csrf
                    <div class="form__input__flex">

                        <div class="form__input__single">
                            <label for="icon" class="d-block form__input__single__label">{{__('Parent Category')}}</label>
                            <div class="select2_item mt-4">
                                <select name="category_id" id="category" class="select2_activation">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @if($cat->id==$child_category->category_id) selected @endif>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form__input__single">
                            <label for="name" class="form__input__single__label">{{__('Sub Category')}}</label>
                            <select  name="sub_category_id" id="subcategory" class="select2_activation subcategory">
                                <option @if(!empty($child_category->sub_category_id)) value="{{ $child_category->sub_category_id }}" @else value="" @endif >
                                    {{ optional($child_category->subcategory)->name }}</option>
                            </select>
                        </div>

                        <div class="form__input__single">
                            <label for="name" class="form__input__single__label">{{__('Child Category Name')}}</label>
                            <input type="text" class="form__control radius-5" name="name" id="name" value="{{$child_category->name}}" placeholder="{{__('Child Category Name')}}">
                        </div>

                        <div class="form__input__single permalink_label">
                            <label class="text-dark form__input__single__label">{{__('Permalink * :')}}
                                <span id="slug_show" class="display-inline"></span>
                                <span id="slug_edit" class="display-inline">
                                     <button class="btn btn-warning btn-sm slug_edit_button"> <i class="fas fa-edit"></i> </button>
                                    <input type="text" name="slug" class="form-control child_category_slug mt-2" value="{{$child_category->slug}}" style="display: none">
                                      <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                </span>
                            </label>
                        </div>

                        <div class="form__input__single">
                            <label for="image" class="form__input__single__label">{{__('Upload Child Category Image')}}</label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap">
                                    {!! render_image_markup_by_attachment_id($child_category->image,'','thumb') !!}
                                </div>
                                <input type="hidden" name="image" value="{{$child_category->image}}">
                                <button type="button" class="cmnBtn btn_5 btn_bg_blue radius-5 media_upload_form_btn"
                                        data-btntitle="{{__('Select Image')}}"
                                        data-modaltitle="{{__('Upload Image')}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="btn_wrapper mt-4">
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('assets/backend/js/fontawesome-iconpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/backend/css/fontawesome-iconpicker.min.css')}}">
    <x-summernote.js/>
    <script>
        <x-icon.icon-picker/>
    </script>
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                //Permalink Code
                var sl =  $('.child_category_slug').val();
                var url = `{{url('/child-category/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');

                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    finalSlug = slug.replace(/  +/g, ' ');
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.child_category_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.child_category_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/child-category/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.child_category_slug').val(slug)
                    $('.child_category_slug').hide();
                });

                $('#category').on('change',function(){
                    let category_id = $(this).val();
                    $.ajax({
                        method:'post',
                        url:"{{route('admin.select.subcategory')}}",
                        data:{category_id:category_id},
                        success:function(res){
                            if(res.status=='success'){
                                let alloptions = '';
                                let allSubCategory = res.sub_categories;
                                $.each(allSubCategory,function(index,value){
                                    alloptions +="<option value='" + value.id + "'>" + value.name + "</option>";
                                });
                                $(".subcategory").html(alloptions);
                                $('#subcategory').niceSelect('update');
                            }
                        }
                    })
                });

                // for summernote
                $('.summernote').summernote({
                    height: 400,
                    codemirror: {
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function (index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }

            });
        })(jQuery)
    </script>
@endsection
