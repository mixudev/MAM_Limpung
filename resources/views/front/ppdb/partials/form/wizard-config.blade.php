<script>
    window.ppdbFormConfig = {
        form: @json($ppdbFormOld),
        errorStep: {{ $ppdbErrorStep }},
        showErrorAlert: @json($errors->any()),
        firstErrorField: @json($ppdbFirstErrorField),
    };
</script>
<script src="{{ asset('assets/js/ppdb-form-wizard.js') }}"></script>
