<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>

<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>

<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>


<!-- Sweet Alerts js -->

<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>


<!-- Sweet alert init js-->

<script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>


<script src="{{ asset('assets/libs/morris.js/morris.min.js') }}"></script>

<script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>


<script src="{{ asset('assets/libs/peity/jquery.peity.min.js') }}"></script>


<script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>


<!-- Required datatable js -->

<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<!-- Buttons examples -->

<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>

<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>

<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->

<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>


<!-- Datatable init js -->

<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>


<script src="{{ asset('assets/js/app.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>

<!-----login Api------->
<script>

	function Login() {
		$.ajax({
			type: "POST",
			url: "https://beckcircle.com/whimsfull/api/user/login",
			dataType: 'json',
			data: $("#Login_Form").serialize(),
			beforeSend: function () {

			}, success: function (res) {
				console.log(res);
				if (res.Error == true) {

					$('.prompt').html('<div class="alert alert-danger" style="text-align:left !important"><i class="fa fa-times" style="margin-right:10px;"></i>' + 'Email or Password Incorrect' + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();


					}, 2000);

				} else if (res.Error == false) {

					$('.prompt').html('<div class="alert alert-success" style="text-align:left !important"><i class="fa fa-check" style="margin-right:10px;"></i>' + 'User Registered Successfully' + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();

						localStorage.setItem('token', res.Token);
						window.location.href = "{{ route('dashboard') }}?id=1";


					}, 2000);

				}
			}, error: function (e) {
				console.log(e);

			}
		});
	}

	$("#register_user_from").submit(function (e) {
		e.preventDefault();
		Register_User();
	});







	$("#asset_edit_form").submit(function (e) {
		e.preventDefault();
		editasset();
	});

	function editasset() {
		var form = $('#asset_edit_form')[0];
		var fd = new FormData(form);
		var image1_lenght = $('input[type="file"]').length;

		files = $('input[type="file"]')[0].files[0];
		file2 = $('input[type="file"]')[1].files[0];

		if (files !== '' && files !== undefined && files !== null) {
			fd.append('Image_Storefront', files);
		}
		if (file2 !== '' && file2 !== undefined && file2 !== null) {
			fd.append('Image_Storefront_Lock', file2);
		}


		$.ajax({

			type: "POST",
			url: "https://beckcircle.com/whimsfull/api/assets/edit-asset",
			dataType: 'json',
			data: fd,
			contentType: false,
			processData: false,
			cache: false,
			mimeType: "multipart/form-data",
			beforeSend: function () {
				console.log($("#asset_edit_form").serialize());
			}, success: function (res) {
				if (res.Error == true) {

					$('.prompt').html('<div class="alert alert-danger" style="text-align:left !important"><i class="fa fa-times" style="margin-right:10px;"></i>' + res.Message + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();


					}, 2000);

				} else if (res.Error == false) {

					$('.prompt').html('<div class="alert alert-success" style="text-align:left !important"><i class="fa fa-check" style="margin-right:10px;"></i>' + res.Message + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();

						localStorage.setItem('token', res.Token);
						window.location.href = "{{ route('asset') }}";


					}, 2000);

				}
			}, error: function (e) {
				console.log(e);

			}
		});
	}


	function deleteassset() {
		$.ajax({
			type: "POST",
			url: "https://beckcircle.com/whimsfull/api/assets/delete-asset",
			dataType: 'json',
			data: $("#asset_delete_form").serialize(),
			beforeSend: function () {

			}, success: function (res) {
				console.log(res);
				if (res.Error == true) {

					$('.prompt').html('<div class="alert alert-danger" style="text-align:left !important"><i class="fa fa-times" style="margin-right:10px;"></i>' + res.Message + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();


					}, 2000);

				} else if (res.Error == false) {

					$('.prompt').html('<div class="alert alert-success" style="text-align:left !important"><i class="fa fa-check" style="margin-right:10px;"></i>' + res.Message + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();
						window.location.href = "{{route('asset')}}";

					}, 2000);

				}
			}, error: function (e) {
				console.log(e);

			}
		});
	}





	function set_model_value_av(id, aid) {
		$('#delete_assetVaraint_modal').modal('toggle');
		$('#av_id').val(id);
		$('#a_id').val(aid);
	}

	function set_model_value(id) {
		$('#myModal').modal('toggle');
		$('#mdoel_id').val(id);
	}



	function deleteasssetvariant() {
		$.ajax({
			type: "POST",
			url: "https://beckcircle.com/whimsfull/api/assets-variant/delete-asset-variant",
			dataType: 'json',
			data: $("#delete_asset_variant_form").serialize(),
			beforeSend: function () {

			}, success: function (res) {
				console.log(res);
				if (res.Error == true) {

					$('.prompt').html('<div class="alert alert-danger" style="text-align:left !important"><i class="fa fa-times" style="margin-right:10px;"></i>' + res.Message + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();


					}, 2000);

				} else if (res.Error == false) {

					$('.prompt').html('<div class="alert alert-success" style="text-align:left !important"><i class="fa fa-check" style="margin-right:10px;"></i>' + res.Message + '</div>');

					setTimeout(function () {

						$("div.prompt").fadeOut();
						window.location.href = "{{route('asset_variants')}}";

					}, 2000);

				}
			}, error: function (e) {
				console.log(e);

			}
		});
	}






</script>
