(function($) {
    "use strict";

    var dropdown = document.querySelectorAll('[data-dropdown]');
    if (dropdown) {
        dropdown.forEach(function(el) {
            window.addEventListener("click", function(e) {
                if (el.contains(e.target)) {
                    el.classList.toggle("active");
                    setTimeout(function() {
                        el.classList.toggle("animated");
                    }, 10);
                } else {
                    el.classList.remove("active");
                    el.classList.remove("animated");
                }
            });
        });
    }

    let country = document.querySelector("#country"),
        mobile = $("#mobile"),
        mobileCode = document.querySelector("#mobile_code");
    if (country) {
        let countryOption = country.querySelector(`option[data-code="${getConfig.countryCode}"]`),
            mobileOption = mobileCode.querySelector(`option[data-code="${getConfig.countryCode}"]`);
        countryOption.selected = true;
        mobileOption.selected = true;
        country.addEventListener("change", () => {
            let mobileId = mobileCode.querySelector(`option[data-code="${country.options[country.selectedIndex].getAttribute("data-code")}"]`);
            mobileId.selected = true;
        });
        mobileCode.addEventListener("change", () => {
            let countryCode = country.querySelector(`option[data-code="${mobileCode.options[mobileCode.selectedIndex].getAttribute("data-code")}"]`);
            countryCode.selected = true;
        });
    }

    if (mobile.length) {
        mobile.on('propertychange input', function() {
            const input = $(this);
            input.val(input.val().replace(/[^\d]+/g, ''));
        });
    }
})(jQuery);