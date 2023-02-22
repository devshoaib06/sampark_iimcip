<footer>
    <script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/custom.js') }}"></script>
    <script>
    $(document).ready(function () {
        $('#indusCatIds').select2({
            //placeholder: "Choose Industries...",
        });
        $("body").on('keypress', '.onlyNumber', function(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        });
    });
    </script>
    @stack('page_js')
</footer>
</body>
</html>