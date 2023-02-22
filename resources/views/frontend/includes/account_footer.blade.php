<footer>
    <script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/custom.js') }}"></script>

   
   
    <script src="{{ asset('public/assets/autocomplete/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ asset('public/assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	 
    <script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/assets/jquery_validator/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/assets/jquery_validator/additional-methods.min.js') }}"></script>

     <script type="text/javascript" src="{{asset('public/front_end/fancybox/source/jquery.fancybox.pack.js?v=2.1.7')}}"></script>
	<script type="text/javascript" src="{{asset('public/front_end/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
	<script type="text/javascript" src="{{asset('public/front_end/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6')}}"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.css" rel='stylesheet' />
	  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.js" charset="UTF-8"></script> 

    <script>
    // $.fn.modal.Constructor.prototype._enforceFocus = function() {
    //     $(document).off('focusin.bs.modal').on('focusin.bs.modal', $.proxy((function(e) {
    //         if (this.$element[0] !== e.target && !this.$element.has(e.target).length && !$(e.target).closest('.cke_dialog, .cke').length) {
    //         this.$element.trigger('focus');
    //         }
    //     }), this));
    // };
    $(function() {
        $('.select2').select2();
        $('.indusCatIds').select2({
            //placeholder: "Choose industry verticals",
            //allowClear: true
            //dropdownParent: $("#questionModal")
        });
        $('#opnQmodal').on('click', function () {
            $('#questionModal').modal();
            var editorPostInfo = CKEDITOR.replace( 'post_info', {
                customConfig: "{{ asset('public/assets/ckeditor/minipost_config.js') }}",
            } );
        });
        var path = "{{ route('front.user.autoComplete') }}";
        $('#autocomplete').typeahead({
            minLength: 2,
            source:  function (query, process) {
            return $.get(path, { query: query }, function (data) {
                    return process(data);
                });
            },
            afterSelect: function (data) {
                console.log(data.name);
                $('form[name="frmx_src"]').submit();
            }
        });
        $("body").on('keypress', '.onlyNumber', function(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        });
		
		
		
		 $(".checkbox-menu").on("change", "input[type='checkbox']", function () {
            $(this).closest("li").toggleClass("active", this.checked);
        });

        $(document).on('click', '.allow-focus', function (e) {
            e.stopPropagation();
        });
		
		
	   
		/* $(document).on('click', '.filterCheckbox', function (e) {
			var val = [];
			$(':checkbox:checked').each(function(i){
			  val[i] = $(this).val();
			});
			FinalCategory = val.toString();
			//var dataString = +val;
			//console.log(val);
			
			 $.ajax({
				  url: '' ,
				  data: "postdata="+ FinalCategory,
				  type: 'GET',
				  dataType: 'html',
				  success: function(response) {
					console.log(response);
					$('#ajaxPost').html(response);
					
				  }
				});   
				
		}); */
	   
	   
	   /* function changePosts(){
		   alert(1);
		   
	   } */

        
    });    
    </script>

    @stack('page_js')
</footer>

</body>

</html>