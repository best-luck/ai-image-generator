<script>
    "use strict";
    const config = {
        lang: "{{ getLang() }}",
        baseURL: "{{ url('/') }}",
        countryCode: "{{ ipInfo()->location->country_code == 'Other' ? 'US' : ipInfo()->location->country_code }}",
        primaryColor: "{{ $settings->colors->secondary_color }}",
        secondaryColor: "{{ $settings->colors->primary_color }}",
        copiedToClipboardSuccess: "{{ lang('Copied to clipboard') }}",
        actionConfirm: "{{ lang('Are you sure?') }}",
        generatorPromptError: "{{ lang('Enter what do you want to generate', 'home page') }}",
        generatorSamplesError: "{{ lang('Please choose how many samples you want to generate', 'home page') }}",
        generatorSizeError: "{{ lang('Image size cannot be empty', 'home page') }}",
        generatorVisibilityError: "{{ lang('Visibility cannot be empty', 'home page') }}",
        translates: {
            viewImage: "{{ lang('View Image') }}",
        }
    };
</script>
@stack('config')
<script>
    "use strict";
    let configObjects = JSON.stringify(config),
        getConfig = JSON.parse(configObjects);
</script>
