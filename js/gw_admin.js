jQuery(document).ready(function($) {

	

		$('#showadd_node_form').click(function () {

			$('#newnode').slideToggle("slow");

		});

	

		$('#title').keyup(function () {

			if ($('#detail').val().length > 0) {

			  $('#gw_title').text($(this).val());

			}

		});

		

		$('#detail').keyup(function () {

			  $('#gw_detail').text($(this).val());

			  $('#gw_title').text($('#title').val());

		});

	

		var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;

		$('#gw_upload_logo_button').click(function(e) {

			var send_attachment_bkp = wp.media.editor.send.attachment;

			var button = $(this);

			var id = button.attr('id').replace('_button', '');

			

			_custom_media = false;

			wp.media.editor.send.attachment = function(props, attachment){

				 $('#gw_newnode_image').attr('src', attachment.url);

				

       			 $('#gw_logo_id').val(attachment.id);



			}

			wp.media.editor.open(button);

			return false;

		});

		

	

		

		$('#gw_update_logo_button').click(function(e) {

			var send_attachment_bkp = wp.media.editor.send.attachment;

			var button = $(this);

			var id = button.attr('id').replace('_button', '');

			

			_custom_media = false;

			wp.media.editor.send.attachment = function(props, attachment){

				 $('.attachment-GroundWork').attr('src', attachment.url);

				

       			 $('#gw_logo_id').val(attachment.id);



			}

			wp.media.editor.open(button);

			return false;

		});

		

$('#gw_tile_image_button').click(function(e) {

			var send_attachment_bkp = wp.media.editor.send.attachment;

			var button = $(this);

			var id = button.attr('id').replace('_button', '');

			

			_custom_media = false;

			wp.media.editor.send.attachment = function(props, attachment){

				 $('#gw_tile_image').attr('src', attachment.url);

				

       			 $('#gw_tile_image_id').val(attachment.id);



			}

			wp.media.editor.open(button);

			return false;

		});

		

		

		$(".gw_tile_display_mode").click(function() {

			mode = this.id;

			$.post(

					ajaxurl,

					{ action: 'gw_avalible_tile_list_ajax', mode: mode },

					function(data){

						

						$("#gw_avalible_tiles_sort").html(data);

						

					}

				);

				 return false;

		});

		

		$(".gw_add_resource").click(function() {

			section = this.id.substring(7);

			$.post(

					ajaxurl,

					{ action: 'load_tile_sort', section: section },

					function(data){

						var jsonObject = eval('(' + data + ')');

						$("#gw_assigned_tiles_sort").html(jsonObject.tile_list);

						$("#gw_add_tiles_heading").html(jsonObject.section_heading);

						$("#gw_assigned_tiles_sort").prop('title', section);

					}

				);

				 return false;

		});



		$( '#gw_assigned_tiles_sort, #gw_avalible_tiles_sort' ).sortable({

			connectWith: ".gw_connectedSortable",

			update: function () { //update the database via AJAX

				list = $(this).sortable('serialize');

				ul = this.id;

				section = $(this).prop('title'); // get

				$.post(

					ajaxurl,

					{ action: 'store_resource_sort', list: list, ul: ul, section: section }

				);

			}

		});

		

	//	$( '.gw_sort_title' ).tooltip();

		

		$('ol.gw_sortable').nestedSortable({

			forcePlaceholderSize: true,

			handle: 'div',

			helper:	'clone',

			items: 'li',

			opacity: .6,

			placeholder: 'placeholder',

			revert: 250,

			tabSize: 25,

			tolerance: 'pointer',

			toleranceElement: '> div',

			maxLevels: 3,

			isTree: true,

			expandOnHover: 700,

			startCollapsed: true,

			update: function () { //update the database via AJAX

       			list = $(this).nestedSortable('serialize');

				$.post(

					ajaxurl,

					{ action: 'store_sort', list: list },

					function(data){

						$("#result").hide().html(data).fadeIn('slow')

					},

					"html"

				);

			}



		});



		$('.disclose').on('click', function() {

			$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');

		});

	

	$("input[name=media_type]").change(function() {

        var media_type = $(this).val();

        $(".media_type_entry").slideUp();

        $("#gw_media_"+media_type).slideDown();

    });

	

	var initial_media_type = $("input[name=media_type]:checked").val(); 

	$("#gw_media_"+initial_media_type).slideDown();

	

	

	var book_ids = [];

	$('.custom_library_book').click(function (e) {

		e.preventDefault();

		$(this).children('li').toggleClass("gw_selected_book");

	

		var shortcode_container = $("#custom_shortcode");

	

		var index = $.inArray(this.id, book_ids);

		if(index === -1){

			// Not in array, add it

			book_ids.push(this.id);

		}

		else{

			// In the array, remove it

			book_ids.splice(index, 1);

		}

	

		shortcode_container.text(book_ids.join(','));

	});

	

	//show the restriction type selection for restricted book creation

	$('#members_only_check').on('click', function() {

		if( $(this).is(':checked')) {

			$("#access_type").slideDown();

		} else {

			$("#access_type").slideUp();

		}

	}); 

	

	//show the user or role selection for restricted access book creation

	$('#access_type').on('click', function() {

		var type = $('input:radio[name=access_type]:checked').val();

		if (type == 'users') {

			tb_show("Select Users","#TB_inline?width=600&height=550&inlineId=user_list",null);

		} else if (type == 'roles') {

			tb_show("Select Roles","#TB_inline?width=600&height=550&inlineId=role_list",null);

		}

						

	});

	

    

		

});