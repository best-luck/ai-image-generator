(function($) {
    "use strict";

    // AOS
    let aosInit = () => {
        if ($('[data-aos]').length > 0) {
            AOS.init({ once: true, disable: 'mobile' });
        }
    }

    aosInit();

    // Dropdown
    var dropdown = document.querySelectorAll('[data-dropdown]');
    if (dropdown != null) {
        dropdown.forEach(function(el) {
            let dropdownMenu = el.querySelector(".drop-down-menu");

            function dropdownOP() {
                if (el.getBoundingClientRect().top + dropdownMenu.offsetHeight > window.innerHeight - 60 && el.getAttribute("data-dropdown-position") !== "top") {
                    dropdownMenu.style.top = "auto";
                    dropdownMenu.style.bottom = "40px";
                } else {
                    dropdownMenu.style.top = "40px";
                    dropdownMenu.style.bottom = "auto";
                }
            }
            window.addEventListener("click", function(e) {
                if (el.contains(e.target)) {
                    el.classList.toggle('active');
                    setTimeout(function() {
                        el.classList.toggle('animated');
                    }, 0);
                } else {
                    el.classList.remove('active');
                    el.classList.remove('animated');
                }
                dropdownOP();
            });
            window.addEventListener("resize", dropdownOP);
            window.addEventListener("scroll", dropdownOP);
        });
    }

    // Navbar
    let navbar = document.querySelector(".nav-bar");
    if (navbar) {
        let navbarOp = () => {
            if (window.scrollY > 0) {
                navbar.classList.add("scrolling");
            } else {
                navbar.classList.remove("scrolling");
            }
        };
        window.addEventListener("scroll", navbarOp);
        window.addEventListener("load", navbarOp);
    }

    // Navbar Menu
    let navbarMenu = document.querySelector(".nav-bar-menu"),
        navbarMenuBtn = document.querySelector(".nav-bar-menu-btn");
    if (navbarMenu) {
        let navbarMenuClose = navbarMenu.querySelector(".nav-bar-menu-close"),
            navbarMenuOverlay = navbarMenu.querySelector(".overlay"),
            navUploadBtn = document.querySelector(".nav-bar-menu [data-upload-btn]");
        navbarMenuBtn.onclick = () => {
            navbarMenu.classList.add("show");
            document.body.classList.add("overflow-hidden");
        };

        navbarMenuClose.onclick = navbarMenuOverlay.onclick = () => {
            navbarMenu.classList.remove("show");
            document.body.classList.remove("overflow-hidden");
        };
        if (navUploadBtn) {
            navUploadBtn.addEventListener("click", () => {
                navbarMenu.classList.remove("show");
            });
        }
    }

    // Plan Switcher
    let plans = document.querySelectorAll(".plans .plans-item"),
        planSwitcher = document.querySelector(".plan-switcher");
    if (planSwitcher) {
        planSwitcher.querySelectorAll(".plan-switcher-item").forEach((el, id) => {
            el.onclick = () => {
                planSwitcher.querySelectorAll(".plan-switcher-item").forEach((ele) => {
                    ele.classList.remove("active");
                });
                el.classList.add("active");
                plans.forEach((el) => {
                    el.classList.remove("active");
                });
                plans[id].classList.add("active");
            };
        });
    }

    // Lazyload
    let lazyLoad = () => {
        let lazy = $('.lazy');
        if (lazy.length) {
            lazy.Lazy({
                afterLoad: function(element) {
                    element.addClass('loaded');
                },
            });
        }
    }

    lazyLoad();

    let avatarInput = $('#change_avatar'),
        targetedImagePreview = $('#avatar_preview');
    if (avatarInput.length) {
        avatarInput.on('change', function() {
            var file = true,
                readLogoURL;
            if (file) {
                readLogoURL = function(input_file) {
                    if (input_file.files && input_file.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            targetedImagePreview.attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input_file.files[0]);
                    }
                }
            }
            readLogoURL(this);
        });
    }

    let generatorForm = $('#generator');

    generatorForm.on('submit', function(e) {

        var reportValidity = generatorForm[0].reportValidity();

        if (reportValidity) {

            e.preventDefault();

            let action = $(this).attr('action'),
                formData = generatorForm.serializeArray(),
                generatorPromptInput = $('#generator input'),
                generatorSamples = $('#generator select[name=samples]'),
                generatorImagesSize = $('#generator select[name=size]'),
                generatorVisibility = $('#generator select[name=visibility]'),
                generatorBtn = $('#generator button'),
                generatorProcessing = $('.processing');

            let defaultImages = $('#default-images'),
                generatedImages = $('#generated-images'),
                viewAllImagesButton = $('#viewAllImagesButton'),
                faqs = $('#faqs'),
                blogArticles = $('#blogArticles');

            if (generatorPromptInput.val() === '') {
                toastr.error(getConfig.generatorPromptError);
            } else if (generatorSamples.val() === null) {
                toastr.error(getConfig.generatorSamplesError);
            } else if (generatorImagesSize.val() === null) {
                toastr.error(getConfig.generatorSizeError);
            } else if (generatorVisibility.val() === null) {
                toastr.error(getConfig.generatorVisibilityError);
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: action,
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    beforeSend: function() {
                        onAjaxStart();
                    },
                    success: function(response) {
                        onAjaxStop();
                        if ($.isEmptyObject(response.error)) {
                            $.each(response.images, function(index, item) {
                                generatedImages.append('<div class="col"> <div class="ai-image"> <img class="lazy" data-src="' + item.src + '" alt="' + item.prompt + '" /> <div class="spinner-border"></div> <div class="ai-image-hover"> <p class="mb-0">' + item.prompt + '</p> <div class="row g-2 alig-items-center"> <div class="col"> <a href="' + item.link + '" target="_blank" class="btn btn-primary btn-md w-100">' + getConfig.translates.viewImage + '</a> </div> <div class="col-auto"> <a href="' + item.download_link + '" class="btn btn-light btn-md px-3"><i class="fas fa-download"></i></a> </div> </div> </div> </div> </div>');
                                generatedImages.removeClass('d-none');
                                lazyLoad();
                            });
                        } else {
                            onAjaxStop();
                            generatedImages.addClass('d-none');
                            defaultImages.removeClass('d-none');
                            viewAllImagesButton.removeClass('d-none');
                            toastr.error(response.error);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        onAjaxStop();
                        generatedImages.addClass('d-none');
                        defaultImages.removeClass('d-none');
                        viewAllImagesButton.removeClass('d-none');
                        toastr.error(errorThrown);
                    }
                })
            }

            function onAjaxStart() {
                generatorBtn.prop('disabled', true);
                generatorForm.addClass('d-none');
                defaultImages.addClass('d-none');
                viewAllImagesButton.addClass('d-none');
                faqs.addClass('d-none');
                blogArticles.addClass('d-none');
                generatorProcessing.removeClass('d-none');
            }

            function onAjaxStop() {
                generatorBtn.prop('disabled', false);
                generatorProcessing.addClass('d-none');
                generatorForm.removeClass('d-none');
                viewAllImagesButton.removeClass('d-none');
                faqs.removeClass('d-none');
                blogArticles.removeClass('d-none');
                aosInit();
            }
        }
    });

    let editImage = $('.edit-image'),
        editImageModal = $('#editImageModal'),
        editImageModalForm = $('#editImageModal form'),
        editImageModalImg = $('#editImageModal img'),
        editModalVisibility = document.querySelector("#editImageModal select[name=visibility]");

    editImage.on('click', function(e) {
        e.preventDefault();
        let details = $(this).data('details');
        editImageModalForm.attr('action', details.action);
        editImageModalImg.attr('src', details.image);
        let visibility = editModalVisibility.querySelector(`option[value="${details.visibility}"]`);
        visibility.selected = true;
        editImageModal.modal('show');
    });

})(jQuery);