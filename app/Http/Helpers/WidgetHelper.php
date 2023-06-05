<?php

function google_analytics()
{
    $script = null;
    if (extension('google_analytics')->status) {
        $script = '<script async src="https://www.googletagmanager.com/gtag/js?id=' . extension('google_analytics')->credentials->measurement_id . '"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag("js", new Date());
          gtag("config", "' . extension('google_analytics')->credentials->measurement_id . '");
        </script>';
    }
    return $script;
}

function google_captcha()
{
    $script = null;
    if (extension('google_recaptcha')->status) {
        $script = NoCaptcha::renderJs(getLang());
    }
    return $script;
}

function display_captcha()
{
    $script = null;
    if (extension('google_recaptcha')->status) {
        $script = '<div class="mb-3">' . app('captcha')->display() . '</div>';
    }
    return $script;
}

function tawk_io()
{
    $script = null;
    if (extension('tawk_to')->status) {
        $script = "<script type='text/javascript'>
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement('script'),s0=document.getElementsByTagName('script')[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/" . extension('tawk_to')->credentials->api_key . "';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>";
    }

    return $script;
}

function pupUp()
{
    $content = null;
    if (settings('popup')->status && !request()->hasCookie('popup_closed')) {
        $content = '<div id="load-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ' . settings('popup')->body . '
            </div>
        </div>
        </div>
    </div>
    <button id="loadModalBtn" class="visually-hidden" data-bs-toggle="modal" data-bs-target="#load-modal"></button>';
    }
    return $content;
}

function cookiePopUp()
{
    $content = null;
    $link = null;
    if (settings('general')->terms_of_service_link) {
        $link = '<a class="btn btn-outline-primary px-5" href="' . settings('general')->terms_of_service_link . '">' . lang('More') . '</a>';
    }
    if (settings('actions')->gdpr_cookie_status && !request()->hasCookie('cookie_accepted')) {
        $content = '<div class="cookies">
        <div class="cookies-img"> <svg id="Capa_1" enable-background="new 0 0 80 80" height="80" viewBox="0 0 512 512" width="80" fill="' . settings('colors')->primary_color . '" xmlns="http://www.w3.org/2000/svg"> <g> <path d="m438.178 76.192c-47.536-48.158-110.952-75.201-178.567-76.147-7.212-.1-13.522 4.986-14.926 12.091-5.416 27.411-28.68 47.918-56.573 49.869-4.48.314-8.585 2.618-11.184 6.281-2.6 3.663-3.422 8.297-2.239 12.63 5.586 20.476 1.736 43.305-10.561 62.633-12.724 20-32.446 33.267-54.109 36.397-8.034 1.161-13.688 8.503-12.755 16.568.278 2.408.419 4.826.419 7.188 0 34.285-27.892 62.177-62.178 62.177-5.317 0-10.605-.674-15.714-2.004-4.73-1.233-9.763-.081-13.487 3.083-3.726 3.164-5.676 7.943-5.227 12.81 5.829 63.268 34.927 121.852 81.932 164.958 47.303 43.382 108.727 67.274 172.958 67.274 68.38 0 132.667-26.628 181.02-74.981 48.352-48.352 74.98-112.639 74.98-181.019 0-67.744-26.205-131.601-73.789-179.808zm-22.404 339.614c-42.687 42.686-99.44 66.194-159.807 66.194-56.702 0-110.927-21.09-152.684-59.384-36.79-33.739-61.153-78.204-69.795-126.759.671.015 1.344.022 2.016.022 49.797 0 90.498-39.694 92.127-89.102 24.934-7.147 46.979-23.816 61.809-47.125 13.689-21.518 19.664-46.432 17.21-70.384 29.678-7.629 53.879-29.733 64.052-58.777 55.345 3.547 106.87 27.006 146.125 66.776 42.007 42.557 65.141 98.93 65.141 158.733-.001 60.367-23.509 117.121-66.194 159.806z" /> <path d="m176.638 270.685c-28.681 0-52.015 23.334-52.015 52.015s23.334 52.015 52.015 52.015c28.682 0 52.016-23.334 52.016-52.015-.001-28.681-23.335-52.015-52.016-52.015zm0 74.03c-12.139 0-22.015-9.876-22.015-22.015s9.876-22.015 22.015-22.015c12.14 0 22.016 9.876 22.016 22.015-.001 12.139-9.877 22.015-22.016 22.015z" /> <path d="m301.777 182.914c30.281 0 54.917-24.636 54.917-54.917s-24.636-54.917-54.917-54.917-54.917 24.636-54.917 54.917 24.635 54.917 54.917 54.917zm0-79.834c13.739 0 24.917 11.178 24.917 24.917 0 13.74-11.178 24.917-24.917 24.917s-24.917-11.178-24.917-24.917c0-13.74 11.177-24.917 24.917-24.917z" /> <path d="m328.011 312.074c-27.751 0-50.328 22.577-50.328 50.328s22.577 50.328 50.328 50.328 50.328-22.577 50.328-50.328-22.577-50.328-50.328-50.328zm0 70.656c-11.209 0-20.328-9.119-20.328-20.328s9.119-20.328 20.328-20.328 20.328 9.119 20.328 20.328-9.119 20.328-20.328 20.328z" /> <path d="m448.725 234.768c0-27.751-22.577-50.328-50.328-50.328s-50.327 22.577-50.327 50.328 22.576 50.328 50.327 50.328 50.328-22.577 50.328-50.328zm-50.328 20.328c-11.208 0-20.327-9.119-20.327-20.328s9.119-20.328 20.327-20.328c11.209 0 20.328 9.119 20.328 20.328s-9.119 20.328-20.328 20.328z" /> <path d="m383.687 140.08c1.276 6.15 6.276 10.964 12.506 11.905 6.19.934 12.491-2.252 15.423-7.775 2.934-5.525 2.115-12.488-2.108-17.132-4.216-4.636-11.06-6.188-16.852-3.779-6.585 2.74-10.272 9.814-8.969 16.781z" /> <path d="m444.517 324.14c-1.176-6.147-6.34-10.972-12.496-11.898-6.185-.931-12.499 2.243-15.433 7.769-2.934 5.524-2.105 12.499 2.113 17.14 4.223 4.646 11.053 6.158 16.847 3.77 6.502-2.681 10.405-9.862 8.969-16.781z" /> <path d="m191.267 217.7c1.15 6.143 6.358 10.982 12.496 11.905 6.202.933 12.477-2.258 15.433-7.775 2.962-5.528 2.077-12.479-2.11-17.132-4.201-4.668-11.069-6.16-16.85-3.779-6.502 2.68-10.405 9.864-8.969 16.781z" /> <path d="m250.677 423.84c-1.29-6.153-6.263-10.962-12.505-11.909-6.185-.938-12.487 2.257-15.425 7.769-2.944 5.523-2.105 12.498 2.109 17.14 4.209 4.636 11.066 6.181 16.85 3.78 6.49-2.693 10.407-9.862 8.971-16.78z" /> <path d="m316.127 222.41c-1.955-5.978-7.261-10.301-13.613-10.626-6.261-.32-12.191 3.471-14.587 9.246-2.396 5.774-.878 12.66 3.779 16.853 4.671 4.206 11.583 5.054 17.132 2.107 6.271-3.331 9.302-10.803 7.289-17.58z" /> <path d="m279.137 295.89c5.225-3.44 7.747-10.067 6.222-16.118-1.528-6.067-6.839-10.667-13.073-11.271-6.231-.603-12.361 2.833-15.028 8.513-2.662 5.668-1.49 12.581 2.949 17.017 4.983 4.978 13.077 5.806 18.93 1.859z" /> <path d="m90.326 324.14c-1.176-6.147-6.34-10.972-12.496-11.898-6.189-.932-12.498 2.242-15.434 7.769-2.935 5.526-2.11 12.496 2.11 17.14 4.22 4.643 11.059 6.163 16.85 3.77 6.492-2.683 10.42-9.862 8.97-16.781z" /> <path d="m90.607 104.03c28.682 0 52.016-23.334 52.016-52.015-.001-28.681-23.335-52.015-52.016-52.015s-52.015 23.334-52.015 52.015 23.334 52.015 52.015 52.015zm0-74.03c12.14 0 22.016 9.876 22.016 22.015s-9.876 22.015-22.016 22.015c-12.139 0-22.015-9.876-22.015-22.015s9.876-22.015 22.015-22.015z" /> <path d="m1.736 169.21c2.898 5.531 9.277 8.706 15.435 7.772 6.197-.939 11.269-5.751 12.505-11.902 1.233-6.137-1.635-12.584-7.004-15.795-5.383-3.22-12.358-2.699-17.216 1.265-5.546 4.527-6.934 12.366-3.72 18.66z" /> </g> </svg> </div>
        <p class="cookies-text text-center my-3">' . lang('gdpr_cookie_note') . '</p>
        <div class="d-flex justify-content-center">
        <button id="acceptCookie" class="btn btn-primary hvr-radial-out px-5 me-3">' . lang('Got it') . '</button>
        ' . $link . '
        </div>
        </div>';
    }
    return $content;
}

function facebook_login()
{
    $script = null;
    if (extension('facebook_oauth')->status) {
        $script = '<div class="login-with mt-3">
            <div class="login-with-divider mb-3">
            <span>' . lang('Or', 'auth') . '</span>
            </div>
            <a href="' . route('provider.login', 'facebook') . '" class="btn btn-facebook btn-md w-100 text-center">
                <i class="fab fa-facebook-f me-2"></i>' . lang('Sign in With Facebook', 'auth') . '</a>
        </div>';
    }
    return $script;
}
