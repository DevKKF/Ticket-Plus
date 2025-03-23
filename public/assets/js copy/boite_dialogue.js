$(document).ready(function () {

    $('#pointage_table').DataTable({
		order: [[0, 'desc']],
	});

	// Détails d'un agent
	$('.DetailsAgent').click(function(){
		var url = $(this).data('url');
		var details = $(this).data('details');
		// alert(details);
		$('#dialog').krakPopup({
			title:details,
			url:url,
			width: 950,
			height: 50,
			contentMinHeight: 140,
			onOutClickClose:false,
			closeButton:false,
			submitButton:false,
			 customButton:{show:false,text:'Boutton',clickFn:function(){
				// alert('A Click on customButton');
				}
			},
			positionTop: '70px',
		});
	});

    // Détails d'une équipe
	$('.DetailsEquipe').click(function(){
		var url = $(this).data('url');
		var details = $(this).data('details');
		// alert(details);
		$('#dialog').krakPopup({
			title:details,
			url:url,
			width: 950,
			height: 50,
			contentMinHeight: 140,
			onOutClickClose:false,
			closeButton:false,
			submitButton:false,
			 customButton:{show:false,text:'Boutton',clickFn:function(){
				// alert('A Click on customButton');
				}
			},
			positionTop: '70px',
		});
	});
});
