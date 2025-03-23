+function ($) { "use strict";

  $(function(){

		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		var base_url = $("#eco_base_url").val();

		$('.datatable:not(".someClass")').each(function() {

			var oTable = $(this).dataTable({
			"bProcessing": false,
			"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
			"sPaginationType": "full_numbers",
			"language": {
				"url": base_url + "js/datatables/lang/French.json"
			},
			"lengthMenu": [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
			"bFilter" : true,
			"bLengthChange": true,
			"order": [[ 1, "desc" ]],
			});

		});

		$('#crtlBoxRecherche').click(function(){
			$('#boxRecherche').toggle();
		});

		//Added on 16022025
		$('.btnSupprimerZone').click(function(){

			var zone_id = $(this).attr('data-zone_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer cette zone ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_zone',
							data: {zone_id:zone_id},
							success: function(data){

								if(data == 1){
									notification('Zone supprimée avec succès !',"success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 16022025
		$('.btnSupprimerRegion').click(function(){

			var region_id = $(this).attr('data-region_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer cette région ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_zone',
							data: {region_id:region_id},
							success: function(data){

								if(data == 1){
									notification('Région supprimée avec succès !',"success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 16022025
		$('.btnSupprimerTypeAction').click(function(){

			var type_action_id = $(this).attr('data-type_action_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer ce type d'action ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_type_action',
							data: {type_action_id:type_action_id},
							success: function(data){

								if(data == 1){
									notification("Type d'action supprimé avec succès !","success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 16022025
		$('.btnSupprimerOperateur').click(function(){

			var operateur_id = $(this).attr('data-operateur_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer cet opérateur ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_operateur',
							data: {operateur_id:operateur_id},
							success: function(data){

								if(data == 1){
									notification("Opérateur supprimé avec succès !","success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 16022025
		$('.btnSupprimerPrioriteIHS').click(function(){

			var priorite_ihs_id = $(this).attr('data-priorite_ihs_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer cette priorité IHS ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_priorite_ihs',
							data: {priorite_ihs_id:priorite_ihs_id},
							success: function(data){

								if(data == 1){
									notification("Opérateur supprimé avec succès !","success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 19022025
		$('.btnSupprimerTopologieTypologie').click(function(){

			var topologie_typologie_id = $(this).attr('data-topologie_typologie_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer cette topologie-typologie ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_topologie_typologie',
							data: {topologie_typologie_id:topologie_typologie_id},
							success: function(data){

								if(data == 1){
									notification("Topologie-Typologie supprimée avec succès !","success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 19022025
		$('.btnSupprimerSite').click(function(){

			var site_id = $(this).attr('data-site_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer ce site ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_site',
							data: {site_id:site_id},
							success: function(data){

								if(data == 1){
									notification("Site supprimé avec succès !","success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 01032025
		$('.btnSupprimerTicket').click(function(){

			var ticket_id = $(this).attr('data-ticket_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer ce ticket ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_ticket',
							data: {ticket_id:ticket_id},
							success: function(data){

								if(data == 1){
									notification("Ticket supprimé avec succès !","success");
								}else{
									notification('Erreur lors de la suppression',"warning");
								}

							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//Added on 01032025
		$('.btnSupprimerUtilisateur').click(function(){

			var utilisateur_id = $(this).attr('data-utilisateur_id');

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:"Voulez-vous vraiment supprimer ce utilisateur ?",
				type: 'warning',
				buttons: [
					{addClass: 'btn btn-success ', text: 'Oui', onClick: function($noty) {
				   		$noty.close();

						$.ajax({
							headers:{'X-CSRF-TOKEN': csrf_token},
							type:'post',
							url: base_url + 'supprimer_utilisateur',
							data: {utilisateur_id:utilisateur_id},
							success: function(response) {
								if (response.status === 1) {
									notification(response.message, "success");
								} else {
									notification(response.message, "warning");
								}
							},
							error: function(){
								notification("Erreur lors du traitement","error");
							}
						});

				   	}},
				   	{addClass: 'btn btn-danger ', text: 'Non', onClick: function($noty) {
				   		$noty.close();
				   	}}]
			});


		});

		//NOTY
		function notification(text,type,callback){

			noty({
				dismissQueue: false,
				force: true,
				layout:'center',
				modal: true,
				theme: 'defaultTheme',
				text:text,
				type: type,
				buttons: [{addClass: 'btn btn-information ', text: 'OK', onClick: function($noty) {
					location.href = "";
					$noty.close();

				}}]
			});
		}

		//Appliquer les masques de saisie
		$('.select_search').select2({
			placeholder: "Choisir",
			allowClear: true
		});

		//Appliquer les masques de saisie
		$('.select_multiple').select2({
			placeholder: "Choisir",
			allowClear: true
		});

		$('.telephone').mask('date');


	});
}(window.jQuery);
