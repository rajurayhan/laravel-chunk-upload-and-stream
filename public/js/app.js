Dropzone.autoDiscover = false;

var token 	= $('input[name=_token]').val();

$("#file-upload").dropzone({ 
	paramName: 'file',
	url: '/file-upload',
	maxFilesize: 50000, //5000MB
	method: 'POST',
	chunking: true,
	chunkSize: 1000000, //1MB
	retryChunks: true,
	// acceptedFiles: '.mp4',
	addRemoveLinks: true,

	sending: function(file, xhr, formData) {
		formData.append('_token', token);
	},

	uploadprogress: function(file, progress, bytesSent) {
		if(progress!=100){		
			$('#uploaded').text(progress.toFixed(2));
			$('#file').val(progress.toFixed(2));
			var pc = progress.toFixed(2).toString();
			pc  = pc.toString() + '%';
			$(".progress-bar").css({
				"width": pc,
			});
		}
	},

	success: function(file, response) {
		$('#fileName').val(file.name);
		$('#fileType').val(file.type);
		$('#uploaded').text('100');
		$('#file').val(100);
		$(".progress-bar").css({"width": '100%'});
		// alert(file.type);
	},

	complete: function(file) {
		console.log('completed')
		
	},

	error: function(file, error) {
		console.log(error)
		$('#fileName').val('');
	},

	canceled: function(file) {
		console.log('canceled')
		$('#fileName').val('');
	}

});

$("#submitBtn").click(function(){
	var fileName 		= $('#fileName').val();
	var fileType 		= $('#fileType').val();
	var name 			= $('#name').val();
  	if(fileName && name){
  		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': '<?= csrf_token() ?>'
		    }
		});
		$.ajax({
		    // url: "{{ route('post') }}",
		    url: Posturl,
		    data: {fileName, name, fileType},
		    type: 'GET',
		    datatype: 'JSON',
		    success: function (response) {
		    		console.log(response);
		    		$('#fileName').val('');
		    		$('#fileType').val('');
		    		window.location = '/';
		        },

		});
  	}
  	else{
  		alert('Name is required');
  	}
});