(function ($) {
    "use strict";
    jQuery(document).ready(function ($) {
        /*-----------------------------------
          global slick slicer control
      -----------------------------------*/
        var globalSlickInit = $('.global-slick-init');
        if (globalSlickInit.length > 0) {
            //todo have to check slider item
            $.each(globalSlickInit, function (index, value) {
                if ($(this).children('div').length > 1) {
                    //todo configure slider settings object
                    var sliderSettings = {};
                    var allData = $(this).data();
                    var infinite = typeof allData.infinite == 'undefined' ? false : allData.infinite;
                    var arrows = typeof allData.arrows == 'undefined' ? false : allData.arrows;
                    var autoplay = typeof allData.autoplay == 'undefined' ? false : allData.autoplay;
                    var focusOnSelect = typeof allData.focusonselect == 'undefined' ? false : allData.focusonselect;
                    var swipeToSlide = typeof allData.swipetoslide == 'undefined' ? false : allData.swipetoslide;
                    var slidesToShow = typeof allData.slidestoshow == 'undefined' ? 1 : allData.slidestoshow;
                    var slidesToScroll = typeof allData.slidestoscroll == 'undefined' ? 1 : allData.slidestoscroll;
                    var speed = typeof allData.speed == 'undefined' ? '500' : allData.speed;
                    var dots = typeof allData.dots == 'undefined' ? false : allData.dots;
                    var cssEase = typeof allData.cssease == 'undefined' ? 'linear' : allData.cssease;
                    var prevArrow = typeof allData.prevarrow == 'undefined' ? '' : allData.prevarrow;
                    var nextArrow = typeof allData.nextarrow == 'undefined' ? '' : allData.nextarrow;
                    var centerMode = typeof allData.centermode == 'undefined' ? false : allData.centermode;
                    var centerPadding = typeof allData.centerpadding == 'undefined' ? false : allData.centerpadding;
                    var rows = typeof allData.rows == 'undefined' ? 1 : parseInt(allData.rows);
                    var autoplay = typeof allData.autoplay == 'undefined' ? false : allData.autoplay;
                    var autoplaySpeed = typeof allData.autoplayspeed == 'undefined' ? 2000 : parseInt(allData.autoplayspeed);
                    var lazyLoad = typeof allData.lazyload == 'undefined' ? false : allData.lazyload; // have to remove it from settings object if it undefined
                    var appendDots = typeof allData.appenddots == 'undefined' ? false : allData.appenddots;
                    var appendArrows = typeof allData.appendarrows == 'undefined' ? false : allData.appendarrows;
                    var asNavFor = typeof allData.asnavfor == 'undefined' ? false : allData.asnavfor;
                    var verticalSwiping = typeof allData.verticalswiping == 'undefined' ? false : allData.verticalswiping;
                    var vertical = typeof allData.vertical == 'undefined' ? false : allData.vertical;
                    var fade = typeof allData.fade == 'undefined' ? false : allData.fade;
                    var rtl = typeof allData.rtl == 'undefined' ? false : allData.rtl;
                    var variableWidth = typeof allData.variablewidth == 'undefined' ? false : allData.variablewidth;
                    var responsive = typeof $(this).data('responsive') == 'undefined' ? false : $(this).data('responsive');
                    //slider settings object setup
                    sliderSettings.infinite = infinite;
                    sliderSettings.arrows = arrows;
                    sliderSettings.autoplay = autoplay;
                    sliderSettings.focusOnSelect = focusOnSelect;
                    sliderSettings.swipeToSlide = swipeToSlide;
                    sliderSettings.slidesToShow = slidesToShow;
                    sliderSettings.slidesToScroll = slidesToScroll;
                    sliderSettings.speed = speed;
                    sliderSettings.dots = dots;
                    sliderSettings.cssEase = cssEase;
                    sliderSettings.prevArrow = prevArrow;
                    sliderSettings.nextArrow = nextArrow;
                    sliderSettings.rows = rows;
                    sliderSettings.autoplaySpeed = autoplaySpeed;
                    sliderSettings.autoplay = autoplay;
                    sliderSettings.verticalSwiping = verticalSwiping;
                    sliderSettings.vertical = vertical;
                    sliderSettings.variableWidth = variableWidth;
                    sliderSettings.rtl = rtl;

                    if (centerMode != false) {
                        sliderSettings.centerMode = centerMode;
                    }
                    if (centerPadding != false) {
                        sliderSettings.centerPadding = centerPadding;
                    }
                    if (lazyLoad != false) {
                        sliderSettings.lazyLoad = lazyLoad;
                    }
                    if (appendDots != false) {
                        sliderSettings.appendDots = appendDots;
                    }
                    if (appendArrows != false) {
                        sliderSettings.appendArrows = appendArrows;
                    }
                    if (asNavFor != false) {
                        sliderSettings.asNavFor = asNavFor;
                    }
                    if (fade != false) {
                        sliderSettings.fade = fade;
                    }
                    if (responsive != false) {
                        sliderSettings.responsive = responsive;
                    }
                    $(this).slick(sliderSettings);
                }
            });
        }



        /* Navbar button */
        $(document).on('click', '.click-nav-right-icon', function () {
            $(".navbar-right-content").toggleClass("show-nav-content");
        });
        $(document).on('click', '.click-nav-left-icon', function () {
            $(".navbar-left-content").toggleClass("show-nav-content");
        });
        $(document).on('click', '.dashbord-toggle-icon', function () {
            $(".user-sidebar-left-menu-wraper").toggleClass("show");
        });
        $(document).on('click', '.profile-part', function () {
            $(".profile-item").toggleClass("show");
        });
        $(document).on('click', '#mark_all_read', function () {
            $(".single_notification").removeClass("new");
        })
        $(document).on('click', '.mark_read', function () {
            $(this).closest('.single_notification').removeClass('new');
        });
        $(document).on('click', '.heart-img', function () {
            $(this).closest(".heart-img").addClass('active');
            const toast = $('#wishlist-toast');
            toast.addClass('show');

            setTimeout(function () {
                toast.removeClass('show');
            }, 5000);
        });



        //Select2 dropedown
        $('.location-0').select2({
            placeholder: "Location",
        });
        $('.property-0').select2({
            placeholder: "Property"
        });
        $('.price-range-0').select2({
            placeholder: "Price Range",
        });
        $('#rent_property_location').select2({
            placeholder: "Location",
        });
        $('#rent_property-0').select2({
            placeholder: "Property"
        });
        $('#rent_price-range-0').select2({
            placeholder: "Price Range",
        });
        $('#buy_property_location').select2({
            placeholder: "Location",
        });
        $('#buy_property-0').select2({
            placeholder: "Property"
        });
        $('#buy_price-range-0').select2({
            placeholder: "Price Range",
        });

        $('.filter-widget.filter-location').select2({

        });
        $('.filter-widget.filter-location-2').select2({

        });
        $('.filter-widget.bed').select2({

        });
        $('.filter-widget.bath').select2({

        });
        $('.filter-widget.area').select2({
            placeholder: "Area",
        });
        $('.filter-widget.area.homes').select2({
            placeholder: "Area",
        });
        $('.filter-widget.property-categories').select2({

        });
        $('.category-wraper #main-category').select2({
            placeholder: "Main Cetagory",
        });
        $('.category-wraper #sub-category').select2({
            placeholder: "Sub Category",
        });
        $('.category-wraper #child-category').select2({
            placeholder: "Child Category",
        });
        $('.general-information #bedroom').select2({
            placeholder: "4",
        });
        $('.general-information #bathroom').select2({
            placeholder: "4",
        });
        $('.general-information #kitchen').select2({
            placeholder: "4",
        });
        $('.area-input-waper #area-quantity').select2({
            placeholder: "Sqft",
        });
        $('.country-select-wraper #country').select2({
            placeholder: "Bangladesh",
        });
        $('.state-city-wraper #state').select2({
            placeholder: "Dhaka",
        });
        $('.state-city-wraper #city').select2({
            placeholder: "Gazipur",
        });
        //chose amenities
        $(document).on('click', '.single-amenities input', function () {
            let singleAmenities = $(this).closest(".single-amenities")
            singleAmenities.toggleClass("selected");
        });
        $(document).on('click', '.three-dots', function () {
            let option = $(this).siblings(".option");
            option.toggleClass("show");
        });
        $(document).on('click', '.option .publish', function () {
            $(this).toggleClass("published");
        });

    });

    //     /*-----------------------------------
    //         price range slider control
    //     -----------------------------------*/

    // var stepsSlider = document.getElementById('price-range-bar');
    // var input0 = document.getElementById('min-price');
    // var input1 = document.getElementById('max-price');

    // if (stepsSlider) {
    //     noUiSlider.create(stepsSlider, {
    //         start: [0, 150],
    //         connect: true,
    //         range: {
    //             'min': 0,
    //             'max': 500
    //         }
    //     });

    //     stepsSlider.noUiSlider.on('update', function (values, handle) {
    //         [input0, input1][handle].value = Math.round(values[handle]);
    //     });

    //     [input0, input1].forEach((input, index) => {
    //         input.addEventListener('input', function () {
    //             stepsSlider.noUiSlider.setHandle(index, this.value);
    //         });
    //     });
    // }

    // var stepsSlider_2 = document.getElementById('price-range-bar_2');
    // var input3 = document.getElementById('min-price-2');
    // var input4 = document.getElementById('max-price-2');

    // if (stepsSlider_2) {
    //     noUiSlider.create(stepsSlider_2, {
    //         start: [0, 450],
    //         connect: true,
    //         range: {
    //             'min': 0,
    //             'max': 600
    //         }
    //     });

    //     stepsSlider_2.noUiSlider.on('update', function (values, handle) {
    //         [input3, input4][handle].value = Math.round(values[handle]);
    //     });

    //     [input3, input4].forEach((input, index) => {
    //         input.addEventListener('input', function () {
    //             stepsSlider_2.noUiSlider.setHandle(index, this.value);
    //         });
    //     });
    // }


    /*-----------------------------------
      All Listing Filter open and hide
    ----------------------------------*/
    $(document).on('click', '.filter-btn', function () {
        $(".filter-widget-wraper, .black-shadow").toggleClass("show");
    });
    $(document).on('click', '.filter-head .close-icon', function () {
        $(".filter-widget-wraper").removeClass("show");
        $(".black-shadow").toggleClass("show");
    });


    // all payment gateway slection 

    $(document).ready(function () {

        let btn_p = $('.rounding_15 .payment-gateway-wrapper.payment_getway_image ul li');
        $(btn_p).on('click', function () {
            console.log("Button clicked");

            $('.payment-gateway-wrapper ul li').removeClass('active selected');

            $(this).addClass('active selected');

            var selectedGateway = $(this).data('gateway');

            $('#order_from_user_wallet').val(selectedGateway);

            console.log("Selected Gateway: " + selectedGateway);
        });
    });

    // sort for search
    $(document).ready(function () {
        $('.filter_title').on('click', function () {
            $('.filter_title').removeClass('select');
            $(this).addClass('select');
        });
    });
    

}(jQuery));