baseUrl = 'http://api.com/';
$(function(){
	$('#url-options').on('change',function(){
		var $this = $(this),
			index = $this.find(":selected").text();
		$('#url').html(baseUrl+index);
		$('#request-type').html(info[index]['type']);
		$('#success-box').html(JSON.stringify(info[index]['response']['success'],null, 2));
		$('#failure-box').html(JSON.stringify(info[index]['response']['failure'],null, 2));
		$('#payload-box').html(JSON.stringify(info[index]['payload'],null, 2));
	});

});
$(function(){
	$.each(info, function(k,v) {
	    $('#url-options').append($("<option />").text(k));
	});
});

info = {
		"api/create": {
			"type":"Get",
			"response": {
				"success": {
					"status": "ok",
					"message": "null"
				},
				"failure": {
					"status": "failed",
					"message": "null"
				}
			},
			"payload": {
				"name": "",
				"id": ""
			}
		},
				"api/purchase": {
			"type":"Get",
			"response": {
				"success": {
					"status": "ok",
					"message": "null"
				},
				"failure": {
					"status": "failed",
					"message": "null"
				}
			},
			"payload": {
				"name": "",
				"id": ""
			}
		}
	};