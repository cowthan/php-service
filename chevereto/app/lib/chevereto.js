/* --------------------------------------------------------------------

  Chevereto
  http://chevereto.com/

  @author	Rodolfo Berrios A. <http://rodolfoberrios.com/>
			<inbox@rodolfoberrios.com>

  Copyright (C) Rodolfo Berrios A. All rights reserved.
  
  BY USING THIS SOFTWARE YOU DECLARE TO ACCEPT THE CHEVERETO EULA
  http://chevereto.com/license

  --------------------------------------------------------------------- */
  
$(function(){
	
	// Window listeners
	$(window).on("resize", function(){
		CHV.fn.uploader.boxSizer();
		if(typeof user_background_full_fix == "function") {
			user_background_full_fix();
		}
		CHV.fn.bindSelectableItems();
	});
	
	// Set the anywhere objects, just for shorter calling in $.
	var anywhere_upload = CHV.fn.uploader.selectors.root,
		anywhere_upload_queue = CHV.fn.uploader.selectors.queue,
		$anywhere_upload = $(anywhere_upload),
		$anywhere_upload_queue = $(anywhere_upload_queue);
	
	// Toggle anywhere upload on/off
	$(document).on("click", "[data-action=top-bar-upload]", function(e){
		CHV.fn.uploader.toggle();
	});
	
	// Close upload box
	$("[data-action=close-upload], [data-action=cancel-upload]", $anywhere_upload).click(function() {
		if($anywhere_upload.is(":animated")) return;
		$("[data-action=top-bar-upload]", "#top-bar").click();
	});
	
	// Cancel upload remaining
	$("[data-action=cancel-upload-remaining]", $anywhere_upload).click(function() {
		$("[data-action=cancel]", $anywhere_upload_queue).click();
		CHV.fn.uploader.is_uploading = false;
		if(CHV.fn.uploader.results.success.length > 0) {
			CHV.fn.uploader.displayResults();
			return;
		} else {
			CHV.fn.uploader.reset();
		}
	});
	
	// Toggle upload privacy
	$(document).on("click", "[data-action=upload-privacy]:not(disabled)", function(e){
		if(e.isDefaultPrevented()) return;
		current_privacy = $(this).data("privacy");
		target_privacy = current_privacy=="public" ? "private" : "public";
		this_lock = $(".icon", this).data("lock");
		this_unlock = $(".icon", this).data("unlock");
		$(".icon", this).removeClass(this_lock + " " + this_unlock).addClass(current_privacy=="public" ? this_lock : this_unlock);
		$(this).data("privacy", target_privacy);
		
		$("[data-action=upload-privacy-copy]").html($("[data-action=upload-privacy]").html());
		
		$upload_button = $("[data-action=upload]", $anywhere_upload);		
		$upload_button.text($upload_button.data(target_privacy));

		$(this).tipTip("hide");
	});
	
	// Do the thing when the fileupload changes
	$(CHV.fn.uploader.selectors.file+", "+CHV.fn.uploader.selectors.camera).on("change", function(e){
		if(!$(CHV.fn.uploader.selectors.root).data("shown")) {
			CHV.fn.uploader.toggle({callback: function(e) {
				CHV.fn.uploader.add(e);
			}}, e);
		} else {
			CHV.fn.uploader.add(e);
		}
	}).on("click", function(e) {
		if($(this).data('login-needed') && !PF.fn.is_user_logged()) {
			return;
		}
	});
	
	function isFileTransfer(e) {
		var e = e.originalEvent,
			isFileTransfer = false;
		if(e.dataTransfer.types) {
			for(var i=0; i<e.dataTransfer.types.length; i++) {
				if(e.dataTransfer.types[i] == "Files") {
					isFileTransfer = true;
					break;
				}
			}
			
		}
		return isFileTransfer;
	}
	
	// Enable uploader events
	if($(CHV.fn.uploader.selectors.root).exists()) {		
		$("body").on({
			dragenter: function(e) {
				e.preventDefault();
				if(!isFileTransfer(e)) {
					return false;
				}
				if(!$(CHV.fn.uploader.selectors.dropzone).exists()) {
					$("body").append($('<div id="' + CHV.fn.uploader.selectors.dropzone.replace("#", "") + '"/>').css({width: "100%", height: "100%", position: "fixed",/* opacity: 0.5, background: "red",*/ zIndex: 1000, left: 0, top: 0}));
				}
			}
		});
		$(document).on({
			dragover: function(e) {
				e.preventDefault();
				if(!isFileTransfer(e)) {
					return false;
				}
				if(!$(CHV.fn.uploader.selectors.root).data("shown")) {
					CHV.fn.uploader.toggle({reset: false});
				}
			},
			dragleave: function(e) {
				$(CHV.fn.uploader.selectors.dropzone).remove();
				if($.isEmptyObject(CHV.fn.uploader.files)) {
					CHV.fn.uploader.toggle();
				}
			},
			drop: function(e) {
				e.preventDefault();
				CHV.fn.uploader.add(e);
				$(CHV.fn.uploader.selectors.dropzone).remove();
			},
		}, CHV.fn.uploader.selectors.dropzone);
	}
	
	// 
	$(document).on("keyup change", "[data-action=resize-combo-input]", function(e) {
		var $parent = $(this).closest("[data-action=resize-combo-input]");
		var $input_width = $("[name=form-width]", $parent);
		var $input_height = $("[name=form-height]", $parent);
		var ratio = $input_width.data("initial") / $input_height.data("initial");
		
		if($(e.target).is($input_width)) {
			$input_height.prop("value", Math.round(Math.round($input_width.prop("value")/ratio)));
		} else {
			$input_width.prop("value", Math.round(Math.round($input_height.prop("value")*ratio)));
		}
		
	})
	
	// Edit item from queue
	$(document).on("click", anywhere_upload_queue +" [data-action=edit]", function() {
		var $item = $(this).closest("li"),
			$queue = $item.closest("ul"),
			id = $item.data("id"),
			file = CHV.fn.uploader.files[id];
		
		var modal = PF.obj.modal.selectors.root;
		var queueObject = $.extend({}, file.formValues || file.parsedMeta);
		
		// Attempt to inject the category id
		if(typeof queueObject.category_id == typeof undefined) {
			var upload_category = $("[name=upload-category-id]", CHV.fn.uploader.selectors.root).prop("value") || null;
			queueObject.category_id = upload_category;
		}
		
		// Attempt to inject the NSFW flag
		if(typeof queueObject.nsfw == typeof undefined) {
			var upload_nsfw = $("[name=upload-nsfw]:checked", CHV.fn.uploader.selectors.root).prop("value") || null;
			queueObject.nsfw = upload_nsfw;
		}
		
		// Resize before upload
		/*
		var canvas = $("<canvas />")[0];
		canvas.width = 500;
		canvas.height = 500;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(file.parsedMeta.canvas, 0, 0, canvas.width, canvas.height);

		var dataurl = canvas.toDataURL("image/jpeg");
		
		console.log(dataurl);
		*/
				
		PF.fn.modal.call({
			type: "html",
			template: $("#anywhere-upload-edit-item").html(),
			callback: function() {
				
				$.each(queueObject, function(i, v) {
								
					// Title workaround
					if(i == "title") { i = "image_title"; }
					
					var name = "[name=form-" + i.replace(/_/g, "-") + "]";
					var $input = $(name, modal);
					
					if(!$input.exists()) return true;
					
					// Input handler					
					if($input.is(":checkbox")) {
						$input.prop("checked", $input.attr("value") == v);
					} else if($input.is("select")) {
						var $option = $input.find("[value="+v+"]");
						if(!$option.exists()) {
							$option = $input.find("option:first");
						}
						$option.prop("selected", true);
					} else {
						$input.prop("value", v);
					}
					if(i == "width" || i == "height") {
						$input.prop("max", file.parsedMeta[i]).data("initial", file.parsedMeta[i]);
					}
				});
				
				// Warning on GIF images
				if(file.parsedMeta.mimetype !== "image/gif") {
					$("[ data-content=animated-gif-warning]", modal).remove();
				}
				
				// Canvas image preview
				$(".image-preview", modal).append($('<canvas/>',{'class':'canvas'}));
				
				var source_canvas = $(".queue-item[data-id="+id+"] .preview .canvas")[0];
				var target_canvas = $(".image-preview .canvas", modal)[0];
				
				target_canvas.width = source_canvas.width;
				target_canvas.height = source_canvas.height;

				var target_canvas_ctx = target_canvas.getContext('2d');

				target_canvas_ctx.drawImage(source_canvas, 0, 0);

			},
			confirm: function() {
				
				if(!PF.fn.form_modal_has_changed()){
					PF.fn.modal.close();
					return;
				}
				
				// Validations (just in case)
				var errors = false;
				$.each(["width", "height"], function(i, v) {
					var $input = $("[name=form-" + v + "]", modal);
					var input_val = parseInt($input.val());
					var min_val = parseInt($input.attr("min"));
					var max_val = parseInt($input.attr("max"));
					if(input_val > max_val || input_val < min_val) {
						$input.highlight();
						errors = true;
						return true;
					}
				});
				
				if(errors) {
					PF.fn.growl.expirable(PF.fn._s("Check the errors in the form to continue."));
					return false;
				}
				
				if(typeof file.formValues == typeof undefined) {
					// Stock formvalues object
					file.formValues = {
						image_title: null,
						category_id: null,
						width: null,
						height: null,
						nsfw: null,
						image_description: null
					};
				}
				
				$(":input[name]", modal).each(function(i, v) {
					var key = $(this).attr("name").replace("form-", "").replace(/-/g, "_");
					if(typeof file.formValues[key] == typeof undefined) return true;
					file.formValues[key] = $(this).is(":checkbox") ? ($(this).is(":checked") ? $(this).prop("value") : null) : $(this).prop("value");
				});
				
				CHV.fn.uploader.files[id].formValues = file.formValues;
				
				return true;
			}
		});
		
	});
	
	// Remove item from queue
	$(document).on("click", anywhere_upload_queue +" [data-action=cancel]", function() {
		var $item = $(this).closest("li"),
			$queue = $item.closest("ul"),
			id = $item.data("id"),
			queue_height = $queue.height(),
			item_xhr_cancel = false;
		
		if($item.hasClass("completed") || $item.hasClass("failed")) {
			return;
		}
		
		$("#tiptip_holder").hide();
		
		$item.tipTip("destroy").remove();
		
		if(queue_height !== $queue.height()) {
			CHV.fn.uploader.boxSizer();
		}
		if(!$("li", $anywhere_upload_queue).exists()){
			$("[data-group=upload-queue-ready], [data-group=upload-queue], [data-group=upload-queue-ready]", $anywhere_upload).css("display", "");
		}
		
		if(CHV.fn.uploader.files[id] && typeof CHV.fn.uploader.files[id].xhr !== "undefined") {
			CHV.fn.uploader.files[id].xhr.abort();
			item_xhr_cancel = true;
		}
		
		delete CHV.fn.uploader.files[id];
		
		CHV.fn.uploader.queueSize();
		
		if(Object.size(CHV.fn.uploader.files) == 0) { // No queue left
			// Null result ?
			if(CHV.fn.uploader.results.success.length == 0 && CHV.fn.uploader.results.error.length == 0) {
				CHV.fn.uploader.reset();
			}
		} else {
			
			// An abort was called, we need to process the next item?
			if(item_xhr_cancel) {
				if($("li.waiting", $queue).first().length !== 0) {
					CHV.fn.uploader.upload($("li.waiting", $queue).first());
				} else if(CHV.fn.uploader.results.success.length !== 0 || CHV.fn.uploader.results.error.length !== 0) {
					CHV.fn.uploader.displayResults();
				}		
			}

		}

	});
	
	// Uploader
	$(document).on("click", "[data-action=upload]", function(){
		
		$("[data-group=upload], [data-group=upload-queue-ready]", $anywhere_upload).hide();
		$("[data-group=uploading]", $anywhere_upload).show();
		
		CHV.fn.uploader.queueSize();
		CHV.fn.uploader.can_add = false;
		
		$queue_items = $("li", $anywhere_upload_queue);
		$queue_items.addClass("uploading waiting");
		
		CHV.fn.uploader.timestamp = new Date().getTime();
		CHV.fn.uploader.upload($queue_items.first("li"));
		
	});
	
	/*CHV.obj.image_viewer.$container.swipe({
		swipe: function(event, direction, distance, duration, fingerCount) {
			// right prev, left next
			if(direction == "left" || direction == "right") {
				var go = direction == "left" ? "next" : "prev",
					$link = $("[data-action="+go+"]", ".image-viewer-navigation");
				if($link.exists()) {
					window.location = $link.attr("href");
					return;
				}
			}
		},
		threshold: 100,
		excludedElements: ".noSwipe",
		allowPageScroll: "vertical"
	});*/
	
	// User page
	if($("body#user").exists()) {
		if(PF.obj.listing.query_string.page > 1) {
			var State = History.getState();
			console.log(State.data)
			if(State.data && typeof State.data.scrollTop !== "undefined") {
				if($(window).scrollTop() !== State.data.scrollTop) {
					$(window).scrollTop(State.data.scrollTop);
				}
			} else {
				//var scrollTop = $(".follow-scroll").offset().top - $(".follow-scroll").height();
				var scrollTop = $("#background-cover").height() - 160;
				$("html, body").animate({scrollTop: scrollTop}, 0);
			}
		}
		
	}

	if(!PF.fn.isDevice('phone')) {
		if($("#top-bar-shade").exists()) {
			if($("#top-bar-shade").css("opacity")) {
				$("#top-bar-shade").data("initial-opacity", Number($("#top-bar-shade").css("opacity")));
			}
		}
		$(window).scroll(function(){
			var Y = $(window).scrollTop();
			var is_slim_shady = $("#top-bar-shade").exists() && !$("html").hasClass("top-bar-box-shadow-none");
			
			if(Y < 0) return;
			
			var $top_bar = $("#top-bar");
			var	rate = Number(Y / ($("#background-cover, [data-content=follow-scroll-opacity]").height() - $top_bar.height()));
			
			if(rate > 1) rate = 1;
			
			if(is_slim_shady) {
				if($("#top-bar-shade").data("initial-opacity")) {
					rate += $("#top-bar-shade").data("initial-opacity");
				}
				$("#top-bar-shade").css({opacity: rate});
			}
			
			if(rate == 1) return;
			
			$("#background-cover-src").css({
				transform: "translate(0, "+ Y*0.8 + "px"+")"
				//transform: "scale("+(1+rate/16)+") translate(0, "+ Y*0.8 + "px"+")",
				//transition: "all 1s"
			});
			
		});
	}
	
	// Selectable list items 
	CHV.fn.bindSelectableItems();	
	
	// Image viewer page
	if($("body#image").exists()) {
		
		// Aux fn
		var ImageZoomClass = function() {
			if(CHV.obj.image_viewer.$container.hasClass("jscursor-zoom-in")) {
				CHV.obj.image_viewer.$container.addClass("cursor-zoom-in").removeClass("jscursor-zoom-in");
			}
		}
		
		// Data load detected
		if($(CHV.obj.image_viewer.selector + " [data-load=full]").length > 0) {
			
			if(CHV.obj.image_viewer.$loading.exists()) {
				CHV.obj.image_viewer.$loading.removeClass("soft-hidden").css({zIndex: 2});
				PF.fn.loading.inline(CHV.obj.image_viewer.$loading, {color: "white", size: "small", center: true, valign: true});
				CHV.obj.image_viewer.$loading.hide().fadeIn("slow");
			}
			
			CHV.obj.image_viewer.image.html = CHV.obj.image_viewer.$container.html();
			CHV.obj.image_viewer.$container.prepend($(CHV.obj.image_viewer.image.html).css({top: 0, zIndex: 0}));
			CHV.obj.image_viewer.$container.find("img").eq(0).css("zIndex", 1);
			CHV.obj.image_viewer.$container.find("img").eq(1).attr("src", CHV.obj.image_viewer.image.url).css({
				width: CHV.obj.image_viewer.$container.find("img")[0].getBoundingClientRect().width, // getBoundingClientRect -> get the real decimal width
				height: CHV.obj.image_viewer.$container.find("img")[0].getBoundingClientRect().height
			});
			CHV.obj.image_viewer.$container.find("img").eq(1).imagesLoaded(function(){
				CHV.obj.image_viewer.$container.find("img").eq(1).css({width: "", height: ""});
				CHV.obj.image_viewer.$container.find("img").eq(0).remove();
				PF.fn.loading.destroy(CHV.obj.image_viewer.$loading);
				ImageZoomClass();
			});
			
			// Fix viewer width when height changes and boom! a wild scrollbar appears
			$(document).bind("DOMSubtreeModified", function() {
				if($("html").height() > $(window).innerHeight() && !$("html").hasClass("scrollbar-y")) {
					$("html").addClass("scrollbar-y");
					$(document).data({
						width: $(this).width(),
						height: $(this).height()
					});
					CHV.fn.image_viewer_full_fix();
				}
			});
			
			$(window).on("resize", function() {
				CHV.fn.image_viewer_full_fix();
			});
			
			// Viewer navigation
			$(document).on("keyup", function(e) {
				var $this = $(e.target),
					key = e.charCode || e.keyCode;
				if($this.is(":input")) {
					return;
				} else {
					// Next 39, Prev 37
					if(CHV.obj.image_viewer.$navigation.exists() && (key==37 || key==39)) {
						var navigation_jump_url = $("[data-action="+ (key==37 ? "prev" : "next") +"]", CHV.obj.image_viewer.$navigation).attr("href");
						if(typeof navigation_jump_url !== "undefined" && navigation_jump_url !== "") {
							window.location = $("[data-action="+ (key==37 ? "prev" : "next") +"]", CHV.obj.image_viewer.$navigation).attr("href");
						}
					}
				}
			});
			
		} else {
			ImageZoomClass();
		}

	}
	
	$(document).on("click", CHV.obj.image_viewer.container, function(e) {
	
		var zoom = $(this).hasClass("cursor-zoom-in") || $(this).hasClass("cursor-zoom-out");
		if(!zoom) return;
		
		if(zoom && PF.fn.isDevice(["phone", "phablet"])) {
			//window.location = $("img[data-load=full]", this).attr("src");
			return;
		}
		
		var zoom_in = $(this).hasClass("cursor-zoom-in");

		$(this).removeClass("cursor-zoom-in cursor-zoom-out");
		
		if(zoom_in) {
			// We use getBoundingClientRect to get the not rounded value
			var width = $(this)[0].getBoundingClientRect().width,
				height = $(this)[0].getBoundingClientRect().height,
				ratio = $("img", this).attr("width")/$("img", this).attr("height"),
				new_width;

			$(this).data({dimentions: {width: width, height: height}, ratio: ratio});		
			
			if($("img", this).attr("width") > $(window).width()) {
				$(this).css({width: "100%"});
				new_width = $(this).width();
				$(this).css({width: width});
			} else {
				new_width = $("img", this).attr("width");
			}
			
			$(this).addClass("cursor-zoom-out").animate({width: new_width, height: (new_width/ratio) + "px"}, 250);
			
		} else {
			$(this).addClass("cursor-zoom-in").animate($(this).data("dimentions"), 250);
		}
		
		e.preventDefault();
		
	}).on("contextmenu", CHV.obj.image_viewer.container, function(e) {
		if(!CHV.obj.config.image.right_click) {
			e.preventDefault();
			return false;
		}
	});
	
	/*
	// Input copy
	$(document).on("mouseenter mouseleave", ".input-copy", function(e){
		if(navigator.userAgent.match(/(iPad|iPhone|iPod)/i)) {
			return;
		}
		$(".btn-copy", this)[e.type == "mouseenter" ? "show" : "hide"]();
	});
	
	$(document).on("click", ".input-copy .btn-copy", function(){
		var $input = $(this).closest(".input-copy").find("input");
		$(this).hide();
		$input.highlight();
	});
	*/
	
	/**
	 * USER SIDE LISTING EDITOR
	 * -------------------------------------------------------------------------------------------------
	 */
	
	$(document).on("click", ".list-item, [data-action=list-tools] [data-action]", function(e) {
		var $this = $(e.target),
			$list_item = $this.closest(".list-item");
		if($list_item && $list_item.find("[data-action=select]").exists() && (e.ctrlKey || e.metaKey) && e.altKey) {
			CHV.fn.list_editor.toggleSelectItem($list_item, !$list_item.hasClass("selected"));
			e.preventDefault();
			e.stopPropagation();
		}
	});
	
	// On listing ajax, clear the "Clear selection" toggle
	PF.fn.listing.ajax.callback = function(XHR) {
		if(XHR.status !== 200) return;
		CHV.fn.list_editor.listMassActionSet("select");
	};
	
	// Select all
	$(document).on("click", "[data-action=list-select-all]", function() {
		CHV.fn.list_editor.selectItem($(".list-item:visible:not(.selected)"));
		CHV.fn.list_editor.listMassActionSet("clear");
	});
	// Clear all
	$(document).on("click", "[data-action=list-clear-all]", function() {
		PF.fn.close_pops();
		CHV.fn.list_editor.clearSelection();
	});
	
	// List item tools action (single)
	$(document).on("click", "[data-action=list-tools] [data-action]", function(e){
		
		if(e.isPropagationStopped()) return false;
		
		var $this_list_item = $(this).closest(PF.obj.listing.selectors.list_item),
			$this_list_item_tools = $(this).closest("[data-action=list-tools]");
		
		var $this_icon, this_add_class, this_remove_class, this_label_text, dealing_with;
		
		if(typeof $this_list_item.data("type") !== "undefined"){
			dealing_with = $this_list_item.data("type");
		} else {
			console.log("Error: data-type not defined");
			return;
		}

		switch($(this).data("action")){
			
			case "select":
				CHV.fn.list_editor.toggleSelectItem($this_list_item, !$this_list_item.hasClass("selected"));
			break;
			
			case "edit":
			
				var modal_source = "[data-modal=form-edit-single]";
				
				// Populate the modal before casting it
				switch(dealing_with) {
					case "image":
						$("[name=form-image-title]", modal_source).attr("value", $this_list_item.data("title"));
						$("[name=form-image-description]", modal_source).html(PF.fn.htmlEncode($this_list_item.data("description")));
						
						$("[name=form-album-id]", modal_source).find("option").removeAttr("selected");
						$("[name=form-album-id]", modal_source).find("[value="+$this_list_item.data(dealing_with == "image" ? "album-id" : "id")+"]").attr("selected", true);
						
						$("[name=form-category-id]", modal_source).find("option").removeAttr("selected");
						$("[name=form-category-id]", modal_source).find("[value="+$this_list_item.data("category-id") + "]").attr("selected", true);
						
						$("[name=form-nsfw]", modal_source).attr("checked", $this_list_item.data("flag") == "unsafe");
						
						// Just in case...
						$("[name=form-album-name]", modal_source).attr("value", "");
						$("[name=form-album-description]", modal_source).html("");
						$("[name=form-privacy]", modal_source).find("option").removeAttr("selected");
						
					break;
					case "album":
						$("[data-action=album-switch]", modal_source).remove();
						$("[name=form-album-name]", modal_source).attr("value", $this_list_item.data("name"));
						$("[name=form-album-description]", modal_source).html(PF.fn.htmlEncode($this_list_item.data("description")));
						$("[name=form-privacy]", modal_source).find("option").removeAttr("selected");
						$("[name=form-privacy]", modal_source).find("[value="+$this_list_item.data('privacy')+"]").attr("selected", true);
					break;
				}
				
				PF.fn.modal.call({
					type: "html",
					template: $(modal_source).html(),
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {
								CHV.fn.list_editor.updateItem("[data-id="+$this_list_item.data("id")+"]", XHR.responseJSON[dealing_with], "edit");
							}
						}
					},
					confirm: function() {
						
						var $modal = $(PF.obj.modal.selectors.root);
						
						if((dealing_with == "image" || dealing_with == "album") && $("[data-content=form-new-album]", $modal).is(":visible") && $("[name=form-album-name]", $modal).val() == "") {
							PF.fn.growl.call(PF.fn._s("You must enter the album name."));
							$("[name=form-album-name]", $modal).highlight();
							return false;
						}
						
						if(!PF.fn.form_modal_has_changed()){
							PF.fn.modal.close();
							return;
						}
						
						PF.obj.modal.form_data = {
							action: "edit", // use the same method applied in viewer
							edit: $this_list_item.data("type"),
							single: true,
							owner: CHV.obj.resource.user.id,
							editing: {
								id: $this_list_item.data("id"),
								description: $("[name=form-" + dealing_with + "-description]", $modal).val()
							}
						};
						
						switch(dealing_with) {
							case "image":
								PF.obj.modal.form_data.editing.title = $("[name=form-image-title]", $modal).val();
								PF.obj.modal.form_data.editing.category_id = $("[name=form-category-id]", $modal).val() || null;
								PF.obj.modal.form_data.editing.nsfw = $("[name=form-nsfw]", $modal).prop("checked") ? 1 : 0;
							break;
							case "album":
								PF.obj.modal.form_data.editing.name = $("[name=form-album-name]", $modal).val();
								PF.obj.modal.form_data.editing.privacy = $("[name=form-privacy]", $modal).val();
							break;
						}
						
						PF.obj.modal.form_data.editing.new_album = $("[data-content=form-new-album]", $modal).is(":visible");
						
						if(PF.obj.modal.form_data.editing.new_album) {
							PF.obj.modal.form_data.editing.album_name = $("[name=form-album-name]", $modal).val();
							PF.obj.modal.form_data.editing.album_privacy = $("[name=form-privacy]", $modal).val();
							PF.obj.modal.form_data.editing.album_description = $("[name=form-album-description]", $modal).val();
						} else {
							PF.obj.modal.form_data.editing.album_id = $("[name=form-album-id]", $modal).val();
						}
						
						return true;
					}
				});
			break;
			
			case "move": // Move or create album
				
				var modal_source = "[data-modal=form-move-single]";
				
				// Fool the selected album
				$("[name=form-album-id]", modal_source).find("option").removeAttr("selected");
				$("[name=form-album-id]", modal_source).find("[value="+$this_list_item.data(dealing_with == "image" ? "album-id" : "id")+"]").attr("selected", true);
				
				// Just in case...
				$("[name=form-album-name]", modal_source).attr("value", "");
				$("[name=form-album-description]", modal_source).html("");
				$("[name=form-privacy]", modal_source).find("option").removeAttr("selected");
				
				PF.fn.modal.call({
					type: "html",
					template: $(modal_source).html(),
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {
								CHV.fn.list_editor.updateMoveItemLists(XHR.responseJSON, dealing_with, $this_list_item);
							}
						}
					},
					confirm: function() {
						
						var $modal = $(PF.obj.modal.selectors.root);
						
						if($("[data-content=form-new-album]", $modal).is(":visible") && $("[name=form-album-name]", $modal).val() == "") {
							PF.fn.growl.call(PF.fn._s("You must enter the album name."));
							$("[name=form-album-name]", $modal).highlight();
							return false;
						}
						
						if(!PF.fn.form_modal_has_changed()){
							PF.fn.modal.close();
							return;
						}
						
						PF.obj.modal.form_data = {
							action: "edit", // use the same method applied in viewer
							edit: $this_list_item.data("type"),
							single: true,
							owner: CHV.obj.resource.user.id,
							editing: {
								id: $this_list_item.data("id")
							}
						};
						
						PF.obj.modal.form_data.editing.new_album = $("[data-content=form-new-album]", $modal).is(":visible");
						
						if(PF.obj.modal.form_data.editing.new_album) {
							PF.obj.modal.form_data.editing.album_name = $("[name=form-album-name]", $modal).val();
							PF.obj.modal.form_data.editing.album_privacy = $("[name=form-privacy]", $modal).val();
							PF.obj.modal.form_data.editing.album_description = $("[name=form-album-description]", $modal).val();
						} else {
							PF.obj.modal.form_data.editing.album_id = $("[name=form-album-id]", $modal).val();
						}
						
						return true;
						
					}
				});
				
			break;
			
			case "delete":
				
				PF.fn.modal.call({
					type: "html",
					template: $("[data-modal=form-delete-single]").html(),
					button_submit: PF.fn._s("Confirm"),
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {								
								if(dealing_with == "album") {
									$("[name=form-album-id]", "[data-modal]").find("[value="+$this_list_item.data("id")+"]").remove();
									CHV.fn.list_editor.updateUserCounters("image", XHR.responseJSON.success.affected, "-");
								}
								CHV.fn.list_editor.deleteFromList($this_list_item);
								CHV.fn.queuePixel();
							}
						}
					},
					confirm: function() {
					
						PF.obj.modal.form_data = {
							action: "delete",
							single: true,
							delete: $this_list_item.data("type"),
							deleting: {
								id: $this_list_item.data("id")
							}
						};
						
						return true;
					}
				});					
				
			break;
			
			case "flag":
				$.ajax({
					type: "POST",
					data: {action: 'edit', edit: 'image', single: true, editing: {id: $this_list_item.data("id"), nsfw: $this_list_item.data("flag") == "unsafe" ? 0 : 1}}
				}).complete(function(XHR){
					var response = XHR.responseJSON,
						flag = response.image.nsfw == 1 ? "unsafe" : "safe";
					$this_list_item.removeClass("safe unsafe").addClass(flag).attr("data-flag", flag).data("flag", flag);
					// Remember me gansito
					CHV.fn.list_editor.selectionCount();
				});
			break;
			
		}
		
	});
	
	// Item action (multiple)
	$(".pop-box-menu a", "[data-content=list-selection]").click(function(e){
		
		var $content_listing = $(PF.obj.listing.selectors.content_listing_visible);
		
		if(typeof $content_listing.data("list") !== "undefined"){
			dealing_with = $content_listing.data("list");
		} else {
			console.log("Error: data-list not defined");
			return;
		}
		
		var $targets = $(PF.obj.listing.selectors.list_item+".selected", $content_listing),
			ids = $.map($targets, function(e,i) {
					return $(e).data("id");
				});
		
		$(this).closest(".pop-btn").click();
		
		switch($(this).data("action")){
			
			case "get-embed-codes":
				
				// Prepare the HTML
				var template = "[data-modal=form-embed-codes]";
				$("textarea", template).html(""),
					objects = [];
				
				// Build the object
				$targets.each(function() {
					objects.push({image: $.parseJSON(decodeURIComponent($(this).data("object")))});
				});
				
				CHV.fn.fillEmbedCodes(objects, template, "html");
				
				PF.fn.modal.call({
					type: "html",
					template: $(template).html(),
					buttons: false
				});
				
			break;
			
			case "clear":
				CHV.fn.list_editor.clearSelection();
				e.stopPropagation();
			break;
			
			case "move":
			case "create-album":
				
				var template = $(this).data("action") == "move" ? "form-move-multiple" : "form-create-album",
					modal_source = "[data-modal="+template+"]",
					dealing_id_data = (/image/.test(dealing_with) ? "album-id" : "id");
				
				$("[name=form-album-id]", modal_source).find("[value=null]").remove();
				
				// Fool the album selection
				$("[name=form-album-id]", modal_source).find("option").removeAttr("selected");
				
				// Just in case...
				$("[name=form-album-name]", modal_source).attr("value", "");
				$("[name=form-album-description]", modal_source).html("");
				$("[name=form-privacy]", modal_source).find("option").removeAttr("selected");
				
				// This is an extra step...
				var album_id = $targets.first().data(dealing_id_data),
					same_album = true;
				
				$targets.each(function() {
					if($(this).data(dealing_id_data) !== album_id) {
						same_album = false;
						return false;
					}
				});
				
				if(!same_album) {
					$("[name=form-album-id]", modal_source).prepend('<option value="null">'+PF.fn._s('Select existing album')+'</option>');
				}
				
				$("[name=form-album-id]", modal_source).find("[value="+(same_album ? $targets.first().data(dealing_id_data) : "null")+"]").attr("selected", true);
				
				PF.fn.modal.call({
					type: "html",
					template: $(modal_source).html(),
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {
								CHV.fn.list_editor.updateMoveItemLists(XHR.responseJSON, dealing_with, $targets);
							}
						}
					},
					confirm: function() {
						
						var $modal = $(PF.obj.modal.selectors.root),
							new_album = false;
						
						if($("[data-content=form-new-album]", $modal).is(":visible") && $("[name=form-album-name]", $modal).val() == "") {
							PF.fn.growl.call(PF.fn._s("You must enter the album name."));
							$("[name=form-album-name]", $modal).highlight();
							return false;
						}
						
						if($("[data-content=form-new-album]", $modal).is(":visible")) {
							new_album = true;
						}
						
						if(!PF.fn.form_modal_has_changed()){
							PF.fn.modal.close();
							return;
						}

						var album_object = new_album ? "creating" : "moving";
						
						PF.obj.modal.form_data = {
							action: new_album ? "create-album" : "move",
							type: dealing_with,
							owner: CHV.obj.resource.user.id,
							multiple: true,
							album: {
								ids: ids,
								"new": new_album
							}
						};
						
						if(new_album) {
							PF.obj.modal.form_data.album.name = $("[name=form-album-name]", $modal).val();
							PF.obj.modal.form_data.album.privacy = $("[name=form-privacy]", $modal).val();
							PF.obj.modal.form_data.album.description = $("[name=form-album-description]", $modal).val();
						} else {
							PF.obj.modal.form_data.album.id = $("[name=form-album-id]", $modal).val();
						}
						
						return true;
						
					}
				});
				
			break;
			
			case "delete":
				
				PF.fn.modal.call({
					template: $("[data-modal=form-delete-multiple]").html(),
					button_submit: PF.fn._s("Confirm"),
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {
								// unificar
								if(dealing_with == "albums") {
									$targets.each(function() {
										$("[name=form-album-id]", "[data-modal]").find("[value="+$(this).data("id")+"]").remove();
									});
									CHV.fn.list_editor.updateUserCounters("image", XHR.responseJSON.success.affected, "-");
								}
								CHV.fn.list_editor.deleteFromList($targets);
								CHV.fn.queuePixel();
							}
						}
					},
					confirm: function() {
					
						PF.obj.modal.form_data = {
							action: "delete",
							from: "list",
							"delete": dealing_with,
							multiple: true,
							deleting: {
								ids: ids
							}
						};
						
						return true;
					}
				});
			
			break;
				
			case "assign-category":
				
				var category_id = $targets.first().data("category-id"),
					same_category = true;
			
				$targets.each(function() {
					if($(this).data("category-id") !== category_id) {
						same_category = false;
						return false;
					}
				});
				
				PF.fn.modal.call({
					type: "html",
					template: $("[data-modal=form-assign-category]").html(),
					forced: true,
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {
								$targets.each(function() {
									var response = XHR.responseJSON;
									$(this).data("category-id", response.category_id)
								});
								CHV.fn.list_editor.clearSelection();
							}
						}
					},
					confirm: function() {
						var $modal = $(PF.obj.modal.selectors.root),
							form_category = $("[name=form-category-id]", $modal).val() || null;
						
						if(same_category && category_id == form_category) {
							PF.fn.modal.close(function() {
								CHV.fn.list_editor.clearSelection();
							});
							return false;
						}

						PF.obj.modal.form_data = {
							action: "edit-category",
							from: "list",
							multiple: true,
							editing: {
								ids: ids,
								category_id: form_category
							}
						};
						return true;
					}
				});
			break;
				
			case "flag-safe":
			case "flag-unsafe":
				
				var action = $(this).data("action"),
					flag = action == "flag-safe" ? "safe" : "unsafe";
				
				PF.fn.modal.call({
					template: $("[data-modal=form-" + action + "]").html(),
					button_submit: PF.fn._s("Confirm"),
					ajax: {
						url: PF.obj.config.json_api,
						deferred: {
							success: function(XHR) {
								$targets.each(function() {
									$(this).removeClass("safe unsafe").addClass(flag).removeAttr("data-flag").attr("data-flag", flag).data("flag", flag);
								});
								CHV.fn.list_editor.clearSelection();
							}
						}
					},
					confirm: function() {	
						PF.obj.modal.form_data = {
							action: action,
							from: "list",
							multiple: true,
							editing: {
								ids: ids,
								nsfw: action == "flag-safe" ? 0 : 1
							}
						};
						
						return true;
					}
				});
				
			break;
		}
		
		if(PF.fn.isDevice(["phone", "phablet"])) {
			return false;
		}
		
	});
	
	// Image page
	if($("body#image").exists()) {
		$(window).scroll(function(){
			CHV.obj.topBar.transparencyScrollToggle();
		});
	}
	
	$(document).on("click", "[data-action=disconnect]", function() {
		var $this = $(this),
			connection = $this.data("connection");
		
		PF.fn.modal.confirm({
			message: $this.data("confirm-message"),
			ajax: {
				data: {action: 'disconnect', disconnect: connection, user_id: CHV.obj.resource.user.id},
				deferred: {
					success: function(XHR) {
						var response = XHR.responseJSON;
						$("[data-connection="+connection+"]").fadeOut(function() {
							$($("[data-connect="+connection+"]")).fadeIn();
							$(this).remove();
							if($("[data-connection]").length == 0) {
								$("[data-content=empty-message]").show();
							}
							PF.fn.growl.expirable(response.success.message);
						});
					},
					error: function(XHR) {
						var response = XHR.responseJSON;
						PF.fn.growl.expirable(response.error.message);
					}
				}
			}
		});
	});
	
	$(document).on("click", "[data-action=delete-avatar]", function() {
		var $parent = $(".user-settings-avatar"),
			$loading = $(".loading-placeholder", $parent),
			$top = $("#top-bar");
			
		$loading.removeClass("hidden");
		
		PF.fn.loading.inline($loading, {center: true});
		
		$.ajax({
			type: "POST",
			data: {action: "delete", delete: "avatar", owner: CHV.obj.resource.user.id}
		}).complete(function(XHR){
			$loading.addClass("hidden").empty();
			if(XHR.status == 200) {
				if(CHV.obj.logged_user.id == CHV.obj.resource.user.id) {
					$("img.user-image", $top).hide();
					$(".default-user-image", $top).removeClass("hidden");
				}
				$(".default-user-image", $parent).removeClass("hidden").css({opacity: 0});
				$(".btn-alt", $parent).closest("div").hide();
				$("img.user-image", $parent).fadeOut(function() {
					$(".default-user-image", $parent).animate({opacity: 1});
				});
			} else {
				PF.fn.growl.expirable(PF.fn._s("An error occurred. Please try again later."));
			}
		});

	});
	
	$(document).on("change", "[data-content=user-avatar-upload-input]", function(e) {
		
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this),
			$parent = $(".user-settings-avatar"),
			$loading = $(".loading-placeholder", ".user-settings-avatar"),
			$top = $("#top-bar"),
			user_avatar_file = $(this)[0].files[0];
		
		if($this.data("uploading")) {
			return;
		}
		
		if(/^image\/.*$/.test(user_avatar_file.type) == false) {
			PF.fn.growl.call(PF.fn._s("Please select a valid image file type."));
			return;
		}
		
		if(user_avatar_file.size > CHV.obj.config.user.avatar_max_filesize.getBytes()) {
			PF.fn.growl.call(PF.fn._s("Please select a picture of at most %s size.", CHV.obj.config.user.avatar_max_filesize));
			return;
		}
		
		$loading.removeClass("hidden");
		
		PF.fn.loading.inline($loading, {center: true});
		
		$this.data("uploading", true);
		
		// HTML5 method
		var user_avatar_fd = new FormData();
			
		user_avatar_fd.append("source", user_avatar_file);
		user_avatar_fd.append("action", "upload");
		user_avatar_fd.append("type", "file");
		user_avatar_fd.append("what", "avatar");
		user_avatar_fd.append("owner", CHV.obj.resource.user.id);
		user_avatar_fd.append("auth_token", PF.obj.config.auth_token);
		
		avatarXHR = new XMLHttpRequest();
		avatarXHR.open("POST", PF.obj.config.json_api, true);
		avatarXHR.send(user_avatar_fd);
		avatarXHR.onreadystatechange = function(){
			if(this.readyState == 4){
				var response = this.responseType !== "json" ? JSON.parse(this.response) : this.response,
					image = response.success.image;
				
				$loading.addClass("hidden").empty();
				
				if(this.status == 200) {
					change_avatar = function(parent) {
						$("img.user-image", parent).attr("src", image.url).removeClass("hidden").show();
					};
					hide_default = function(parent) {
						$(".default-user-image", parent).addClass("hidden");
					};
					
					// Form
					hide_default($parent);
					$(".btn-alt", $parent).closest("div").show();
					change_avatar($parent);
					// Top
					if(CHV.obj.logged_user.id == CHV.obj.resource.user.id) {
						change_avatar($top);
						hide_default($top);
					}
					PF.fn.growl.expirable(PF.fn._s("Profile image updated."));
				} else {
					PF.fn.growl.expirable(PF.fn._s("An error occurred. Please try again later."));
				}
				
				$this.data("uploading", false);
			}

		}
	});
	
	$(document).on("change", "[data-content=user-background-upload-input]", function(e) {
		
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this),
			$parent = $("[data-content=user-background-cover]"),
			$src = $("[data-content=user-background-cover-src]"),
			$loading = $(".loading-placeholder", $parent),
			$top = $("#top-bar"),
			user_file = $(this)[0].files[0];
		
		if($this.data("uploading")) {
			return;
		}
		
		if(/^image\/.*$/.test(user_file.type) == false) {
			PF.fn.growl.call(PF.fn._s("Please select a valid image file type."));
			return;
		}
		
		if(user_file.size > CHV.obj.config.user.background_max_filesize.getBytes()) {
			PF.fn.growl.call(PF.fn._s("Please select a picture of at most %s size.", CHV.obj.config.user.background_max_filesize));
			return;
		}
		
		$loading.removeClass("hidden");
		
		PF.fn.loading.inline($loading, {center: true, size: 'big', color: '#FFF'});
		
		$this.data("uploading", true);
		
		// HTML5 method
		var user_picture_fd = new FormData();
			
		user_picture_fd.append("source", user_file);
		user_picture_fd.append("action", "upload");
		user_picture_fd.append("type", "file");
		user_picture_fd.append("what", "background");
		user_picture_fd.append("owner", CHV.obj.resource.user.id);
		user_picture_fd.append("auth_token", PF.obj.config.auth_token);
		
		avatarXHR = new XMLHttpRequest();
		avatarXHR.open("POST", PF.obj.config.json_api, true);
		avatarXHR.send(user_picture_fd);
		avatarXHR.onreadystatechange = function(){
			if(this.readyState == 4){
				var response = this.responseType !== "json" ? JSON.parse(this.response) : this.response,
					image = response.success.image;

				if(this.status == 200) {
					var $img = $("<img/>");
					$img.attr('src', image.url).imagesLoaded(function(){
						$loading.addClass("hidden").empty();
						$src.css("background-image", "url("+image.url+")").hide().fadeIn();
						$("[data-content=user-change-background]", $parent).removeClass("hidden");
						$parent.removeClass("no-background");
						$("[data-content=user-upload-background]").hide();
						$("[data-content=user-change-background]").show();
						PF.fn.growl.expirable(PF.fn._s("Profile background image updated."));
						$img.remove();
						if(typeof user_background_full_fix == "function") {
							user_background_full_fix();
							//PF.fn.follow_scroll_update();
						}
					});

				} else {
					$loading.addClass("hidden").empty();
					PF.fn.growl.expirable(PF.fn._s("An error occurred. Please try again later."));
				}
				
				$this.data("uploading", false);
			}

		}
	});
	/*
	$(document).on("click", "[data-action=disconnect]", function() {
		var $this = $(this),
			connection = $this.data("connection");
		
		PF.fn.modal.confirm({
			message: $this.data("confirm-message"),
			ajax: {
				data: {action: 'disconnect', disconnect: connection, user_id: CHV.obj.resource.user.id},
				deferred: {
					success: function(XHR) {
						var response = XHR.responseJSON;
						$("[data-connection="+connection+"]").fadeOut(function() {
							$($("[data-connect="+connection+"]")).fadeIn();
							$(this).remove();
							if($("[data-connection]").length == 0) {
								$("[data-content=empty-message]").show();
							}
							PF.fn.growl.expirable(response.success.message);
						});
					},
					error: function(XHR) {
						var response = XHR.responseJSON;
						PF.fn.growl.expirable(response.error.message);
					}
				}
			}
		});
	});
	*/
	
	CHV.fn.user_background = {
		delete : {
			submit: function() {
				PF.obj.modal.form_data = {
					action: "delete",
					delete: "background",
					owner: CHV.obj.resource.user.id
				};
				return true;
			},
			deferred: {
				success: {
					before: function(XHR) {
						$("[data-content=user-background-cover-src]").css("background-image", "none");
						$("[data-content=user-background-cover]").addClass("no-background").height("");
						$("[data-content=user-upload-background]").removeClass("hidden").show();
						$("[data-content=user-change-background]").hide();
						$("#top-bar").removeClass("transparent background-transparent");
						$("#top-bar-shade").remove();
						//PF.fn.follow_scroll_update();
					},
					done: function(XHR) {
						PF.fn.modal.close(function(){
							PF.fn.growl.expirable(PF.fn._s("Profile background image deleted."));
						});
					}
				},
				error: function(XHR) {
					PF.fn.growl.expirable(PF.fn._s("Error deleting profile background image."));
				}
			}
		}
	};
	
	// Form things
	CHV.str.mainform = "[data-content=main-form]";
	CHV.obj.timezone = {
		'selector' : "[data-content=timezone]",
		'input' : "#timezone-region"
	};
	
	// Detect form changes
	$(document).on("keyup change", CHV.str.mainform + " :input", function() {
		if($(this).is("[name=username]")) {
			$("[data-text=username]").text($(this).val());
		}
	});
	
	// Timezone handler
	$(document).on("change", CHV.obj.timezone.input, function(){
		var value = $(this).val(),
			$timezone_combo = $("#timezone-combo-"+value);
		$timezone_combo.find("option:first").prop("selected", true);
		$(CHV.obj.timezone.selector).val($timezone_combo.val()).change();
	});
	$(document).on("change", "[id^=timezone-combo-]", function(){
		var value = $(this).val();
		$(CHV.obj.timezone.selector).val(value).change();
	});
	
	// Password match
	$(document).on("keyup change blur", "[name^=new-password]", function() {
		var $new_password = $("[name=new-password]"),
			$new_password_confirm = $("[name=new-password-confirm]"),
			hide = $new_password.val() == $new_password_confirm.val(),
			$warning = $new_password_confirm.closest(".input-password").find(".input-warning");
		
		if($(this).is($new_password_confirm)) {
			$new_password_confirm.data("touched", true);
		}
		
		if($new_password_confirm.data("touched")) {
			$warning.text(!hide ? $warning.data("text") : "")[!hide ? 'removeClass' : 'addClass']('hidden-visibility');
		}
	});
	
	// Submit form
	$(document).on("submit", CHV.obj.mainform, function() {
		switch($(this).data("type")) {
			case "password":
				var $p1 = $("[name=new-password]", this),
					$p2 = $("[name=new-password-confirm]", this);
				if($p1.val() !== "" || $p2.val() !== "") {
					if($p1.val() !== $p2.val()) {
						$p1.highlight();
						$p2.highlight();
						PF.fn.growl.expirable(PF.fn._s("Passwords don't match"));
						return false;
					}
				}
			break;
		}
	});
	
	$(document).on("change", "[name=theme_tone]", function() {
		$("html")[0].className = $("html")[0].className.replace(/\btone-[\w-]+\b/g, '');
		$("html").addClass("tone-"+$(this).val());
	});
	$(document).on("change", "[name=theme_top_bar_color]", function() {
		//$("html")[0].className = $("html")[0].className.replace(/\btone-[\w-]+\b/g, '');
		$("#top-bar, .top-bar").removeClass("black white").addClass($(this).val());
	});
	
	$(document).on("click", "[data-action=check-for-updates]", function() {
		PF.fn.loading.fullscreen();
		CHV.fn.system.checkUpdates(function(XHR) {
			PF.fn.loading.destroy("fullscreen");
						
		});
	});
	
	// Topbar native js thing
	if($("body#image").exists() && window.scrollY > 0) {
		$("#top-bar").removeClass("transparent");
	}
	
	// Storage form
	$(document).on("click", "[data-action=toggle-storage-https]", function() {
		CHV.fn.storage.toggleHttps($(this).closest("[data-content=storage]").data('storage-id'));
	});
	$(document).on("click", "[data-action=toggle-storage-active]", function() {
		CHV.fn.storage.toggleActive($(this).closest("[data-content=storage]").data('storage-id'));
	});

});

if(typeof CHV == "undefined") {
	CHV = {obj: {}, fn: {}, str:{}};
}

CHV.obj.image_viewer = {
	selector: "#image-viewer",
	container: "#image-viewer-container",
	navigation: ".image-viewer-navigation",
	loading: "#image-viewer-loading"
};
CHV.obj.image_viewer.$container = $(CHV.obj.image_viewer.container);
CHV.obj.image_viewer.$navigation = $(CHV.obj.image_viewer.navigation);
CHV.obj.image_viewer.$loading = $(CHV.obj.image_viewer.loading);

CHV.fn.bindSelectableItems = function() {
	var el = 'content-listing-wrapper';
	if(!$("#" + el).exists()) {
		$("[data-content=list-selection]").closest(".content-width").wrap("<div id='" + el + "' />");
	}
	
	if(!$("[data-content=list-selection]").exists() || !PF.fn.isDevice(["laptop", "desktop"])) {
		return;
	}
	$("#content-listing-wrapper").selectable({
		filter: PF.obj.listing.selectors.list_item,
		cancel: PF.obj.listing.selectors.list_item + " *, a, .header-link, .top-bar, .content-listing-pagination *, #fullscreen-modal, #top-user, #background-cover",
		delay: 5, // Avoids unattended click reset
		selecting: function(event, ui) {
			var $this = $(ui.selecting);
			var unselect = $this.hasClass("selected");
			CHV.fn.list_editor[(unselect ? "unselect" : "select") + "Item"]($this);
		},
		unselecting: function(event, ui) {
			CHV.fn.list_editor.unselectItem($(ui.unselecting));
		}
	});
};

// this is just an stock if the fn isn't defined in /image
/*CHV.fn.image_viewer_full_fix = function() {
	
	if(!$(".image-viewer.full-viewer").exists()) return;
	
	var canvas = {
			height: Math.max($(window).height() - $("#top-bar").height(), parseInt($(".image-viewer").css("minHeight"))),
			width: $(window).width()
		},
		img = {
			width: CHV.obj.image_viewer.image.width,
			height: CHV.obj.image_viewer.image.height
		},
		ratio = CHV.obj.image_viewer.image.ratio;
	
	if(img.height > canvas.height && (img.height/img.width) < 3) {
		img.height = canvas.height;
	}
	
	if(img.height == canvas.height) {
		img.width = Math.round(img.height * ratio);
	}
	
	if(PF.fn.isDevice('phone') || PF.fn.isDevice('phablet')) {
		if(img.width > canvas.width) {
			img.width = canvas.width;
		}
		img.height = Math.round(img.width/ratio);

	} else {
		if(img.height > canvas.height && (img.height/img.width) < 3) {
			img.height = canvas.height;
		}	
		
		if(img.height == canvas.height) {
			img.width = Math.round(img.height * ratio);
		}
	}
	
	if(img.width > canvas.width) {
		img.width = canvas.width;
		img.height = Math.round(img.width / CHV.obj.image_viewer.image.ratio);
	} else if((img.height/img.width) > 3) { // wow, very tall. such heights
		img = imgSource;
		if(img.width > canvas.width) {
			img.width = canvas.width * 0.8;
		}
		img.height = Math.round(img.width/ratio);
	}
	
	$(".image-viewer.full-viewer").height(img.height);
	img.display = "block";
	$(".image-viewer-container").css(img);
	
};*/

CHV.obj.embed_tpl = {};

CHV.obj.topBar = {
	transparencyScrollToggle: function() {
		var Y = $(window).scrollTop();
		$("#top-bar")[(Y > 0 ? "remove" : "add") + "Class"]("transparent");
	}
};

CHV.fn.uploader = {
	
	options: {
		image_types: ["png", "jpg", "jpeg", "gif", "bmp"],
		max_filesize: "2 MB"
	},
	
	selectors: {
		root: "#anywhere-upload",
		queue: "#anywhere-upload-queue",
		queue_complete: ".queue-complete",
		queue_item: ".queue-item",
		close_cancel: "[data-button=close-cancel]",
		file: "#anywhere-upload-input",
		camera: "#anywhere-upload-input-camera",
		upload_item_template: "#anywhere-upload-item-template",
		item_progress_bar: "[data-content=progress-bar]",
		item_progress_percent: "[data-text=progress-percent]",
		failed_result: "[data-content=failed-upload-result]",
		fullscreen_mask: "#fullscreen-uploader-mask",
		dropzone: "#uploader-dropzone",
		
	},
	
	is_uploading: false,
	can_add: true,
	queue_status : "ready",
	
	files: {},
	results: {success: [], error: []},
	
	toggleWorking: 0,
	
	toggle: function(options, args) {
		
		var $switch = $("[data-action=top-bar-upload]", ".top-bar"),
			show = $(CHV.fn.uploader.selectors.root).data("shown") ? false : true;
		
		var options = $.extend({callback: null, reset: true}, options);
		
		PF.fn.growl.close(true);
		PF.fn.close_pops();
		
		if(this.toggleWorking == 1 || $(CHV.fn.uploader.selectors.root).is(":animated") || CHV.fn.uploader.is_uploading || ($switch.data('login-needed') && !PF.fn.is_user_logged())) return;
		
		this.toggleWorking = 1;
		
		var animation = {
				core: {
					top: !show ? "-100%" : $(CHV.fn.uploader.selectors.root).css("top")
				},
				time: 300,
				easing: show ? "easeOutExpo" : "easeInExpo"
			},
			callbacks = function() {
				if(options.reset) {
					CHV.fn.uploader.reset();
				}
				if(PF.obj.follow_scroll.$node.exists()) {
					PF.obj.follow_scroll.$node.removeClass("fixed");
					PF.obj.follow_scroll.set();
				}
				if(!show) {
					$(CHV.fn.uploader.selectors.root).css({visibility: "hidden"}).addClass("hidden-visibility");
				}
				$(CHV.fn.uploader.selectors.root).css({top: ""});
				PF.fn.topMenu.hide();
				if(typeof options.callback == "function") {
					options.callback(args);
				}
				CHV.fn.uploader.boxSizer();
				CHV.fn.uploader.toggleWorking = 0;
			};
			
		if(show) {
			$("html").data({
				"followed-scroll": $("html").hasClass("followed-scroll"),
				"top-bar-box-shadow-prevent": true
			}).removeClass("followed-scroll").addClass("top-bar-box-shadow-none");
			
			$("#top-bar").data({
				"stock_classes": $("#top-bar").attr("class")
			});
			
			var top_bar_color = $("#top-bar").hasClass("white") ? "white" : "black";
			var is_slim_shady = $("#top-bar-shade").exists();
			
			//if($("#top-bar").hasClass("transparent")) {
				if(!is_slim_shady) {
					$("<div/>", {
						id: "top-bar-shade",
						"class": "top-bar " + top_bar_color
					}).insertBefore("#top-bar");
				}
				//$("#top-bar").attr("class", "top-bar").addClass(top_bar_color);
			//}
			var shade_target_opacity = 1;
			if($("body").hasClass("landing") || $("body").hasClass("split_landing")) {
				shade_target_opacity = 0;
			}
			$("#top-bar-shade").animate({opacity: shade_target_opacity}, animation.time, animation.easing);
			
			$(".current[data-nav]", ".top-bar").each(function(){
				if($(this).is("[data-action=top-bar-menu-full]")) return;
				$(this).removeClass("current").attr("data-current", 1);
			});
			$(CHV.fn.uploader.selectors.root).removeClass("hidden-visibility").css({visibility: "visible", top: "-" + 100 + "%"});
			if(PF.fn.isDevice("mobile")) {
				var $upload_heading = $(".upload-box-heading", $(CHV.fn.uploader.selectors.root));
				$upload_heading.css({position: "relative", top: 0.5*($(window).height() - $upload_heading.height())+"px"});
			}
			CHV.fn.uploader.focus(function() {
				$(CHV.fn.uploader.selectors.root).animate(animation.core, animation.time, animation.easing, function() {
					callbacks();
					if(PF.fn.isDevice(["phone", "phablet"])) {
						$("html").addClass("overflow-hidden");
					}
				});
			});
		} else { // hide 
			$("[data-nav][data-current=1]", ".top-bar").each(function(){
				$(this).addClass("current");
			});
			
			var fade_slim_shady = function() {
				$("#top-bar-shade").animate({opacity: 0}, animation.time, animation.easing, function() {
					if(!is_slim_shady) {
						$(this).remove();
					}
				});
			}
			
			if(!$("#top-bar").hasClass("transparent")) {
				fade_slim_shady()
			}
			
			
			$(CHV.fn.uploader.selectors.root).animate(animation.core, animation.time, animation.easing, function() {
				if($("#top-bar-shade").exists()) {
					fade_slim_shady()
				}
				$("#top-bar").attr("class", $("#top-bar").data("stock_classes"));
				if($("body#image").exists()) {
					CHV.obj.topBar.transparencyScrollToggle();
				}
				callbacks();
				$(CHV.fn.uploader.selectors.fullscreen_mask).fadeOut(animation.time, function(){
					$(this).remove();
					if($("html").data("followed-scroll")) {
						$("html").addClass("followed-scroll");
					}
				});
				$("html")
					.removeClass("overflow-hidden" + ($(".follow-scroll-wrapper.position-fixed").exists() ? "" : " top-bar-box-shadow-none"))
					.data({"top-bar-box-shadow-prevent": false});
			});
			
			
			
		}
		
		$(CHV.fn.uploader.selectors.root).data("shown", show);
		
		$switch.toggleClass("current").removeClass("opened");
	},
	
	reset: function() {
		
		this.files = {};
		this.is_uploading = false;
		this.can_add = true;
		this.results = {success: [], error: []};
		this.queue_status = "ready";
		
		$("li", this.selectors.queue).remove();
		$(this.selectors.anywhere).height("").css({"overflow-y": "", "overflow-x": ""});
		
		$(this.selectors.queue).removeClass(this.selectors.queue_complete.substring(1));
		
		$("[data-group=upload-result] textarea", this.selectors.anywhere).prop("value", "");
		$.each(['upload-queue-ready', 'uploading', 'upload-result', 'upload-queue-ready', 'upload-queue'], function(i,v) {
			$("[data-group="+v+"]").hide();
		});
		//$("[data-group=upload-queue-ready], [data-group=uploading], [data-group=upload-result], [data-group=upload-queue-ready], [data-group=upload-queue]", this.selectors.anywhere).hide();
		$("[data-group=upload]", this.selectors.anywhere).show();
		$("[name=upload-category-id]", this.selectors.root).val("");
		$("[name=upload-nsfw]", this.selectors.root).prop("checked", "");
		
		$(this.selectors.close_cancel, this.selectors.anywhere).hide().each(function() {
			if($(this).data("action") == "close-upload") $(this).show();
		});
		
		this.boxSizer(true);
	},
	
	focus: function(callback) {
		if($(this.selectors.fullscreen_mask).exists()) return;
		$("body").append($("<div/>", {
			id: (this.selectors.fullscreen_mask.replace("#", "")),
			class: "fullscreen soft-black",
		}).css({
			top: PF.fn.isDevice("phone") ? 0 : $(CHV.fn.uploader.selectors.root).data("top")
		}).fadeIn("fast", function() {
			if(typeof callback == "function") {
				callback();
			}
		}));
	},
	
	boxSizer: function(forced) {
		
		if($(this.selectors.root).css("visibility") == "visible") {
			$("html")[(PF.fn.isDevice(["phone", "phablet"]) ? "add" : "remove") + "Class"]("overflow-hidden");
		}
		
		var doit = $(this.selectors.root).css("visibility") == "visible" || forced;
		
		if(!doit) return;
		
		$(this.selectors.root).height("");
		
		if($(this.selectors.root).height() + parseInt($(this.selectors.root).css("top")) > $(window).height()) {
			$(this.selectors.root).height($(window).height() - parseInt($(this.selectors.root).css("top"))).css({"overflow-y": "scroll", "overflow-x": "hidden"});
			$("body").addClass("overflow-hidden");
		} else {
			$(this.selectors.root).css("overflow-y", "");
			$("body").removeClass("overflow-hidden");
		}
	},
	
	pasteURL: function() {
		var urlvalues = $("[name=urls]", "#fullscreen-modal").val();
		if(urlvalues) {
			CHV.fn.uploader.add({}, urlvalues);
		}
	},
	
	item_add_id : 0,
	add: function(e, urls) {
		
		if(typeof CHV.obj.config !== "undefined" && typeof CHV.obj.config.image !== "undefined" && CHV.obj.config.image.max_filesize !== "undefined") {
			this.options.max_filesize = CHV.obj.config.image.max_filesize;
		}
		
		// Prevent add items ?
		if(!this.can_add) {
			var e = e.originalEvent;
			e.preventDefault();
			e.stopPropagation();
			return false;
		};
		
		$fileinput = $(this.selectors.file);
		$fileinput.replaceWith($fileinput = $fileinput.clone(true));
		
		var item_queue_template = $(this.selectors.upload_item_template).html(),
			files;
		
		if(typeof urls == typeof undefined){
			// Local files
			var e = e.originalEvent;
			e.preventDefault();
			e.stopPropagation();
			files = e.dataTransfer || e.target;
			files = $.makeArray(files.files);
			
			// Filter non images
			var failed_files = [];
			
			files = $.map(files, function(file,i){				
				var image_type_str;
				
				if(typeof file.type == "undefined" || file.type == "") { // Some browsers (Android) don't set the correct file.type
					image_type_str = file.name.substr(file.name.lastIndexOf('.') + 1).toLowerCase();
				} else {
					image_type_str = file.type.replace("image/", "");
				}
				// And yes... Android can output shit like image:10 as the full file name so ignore this filter
				if(CHV.fn.uploader.options.image_types.indexOf(image_type_str) == -1 && /android/i.test(navigator.userAgent) == false) {
					return null;
				}
				if(file.size > CHV.obj.config.image.max_filesize.getBytes()){
					failed_files.push({id: i, name: file.name.truncate_middle() + " - " + PF.fn._s("File too big.")});
					return null;
				}
				return file;
			});
			
			if(failed_files.length > 0 && files.length == 0) {
				var failed_message = '';
				for(var i = 0; i < failed_files.length; i++){
					failed_message += "<li>" + failed_files[i].name + "</li>";
				}
				PF.fn.modal.simple({title: PF.fn._s("Some files couldn't be added"), message: "<ul>" + "<li>" + failed_message + "</ul>"});
				return;
			}
			
			if(files.length == 0) return;
			
		} else {
			// Remote files
			files = urls.match_image_urls();
			if(!files) return;
			files = files.array_unique();
			files = $.map(files, function(file,i){
				return {name: file, url: file};
			});
		}
		
		// Empty current files object?
		if($.isEmptyObject(this.files)) { 
			for(var i=0; i<files.length; i++) {
				this.files[i] = files[i];
				this.files[i].id = i;
				this.item_add_id++;
			}
		} else {
			/**
			 * Check duplicates by file name (local and remote)
			 * This is basic but is the quickest way to do it
			 * Note: it doesn't work on iOS for local files http://stackoverflow.com/questions/18412774/get-real-file-name-in-ios-6-x-filereader
			 */
			var currentfiles = [];
			for(var key in this.files){
				if(typeof this.files[key] == "undefined" || typeof this.files[key] == "function") continue;
				currentfiles.push(encodeURI(this.files[key].name));
			}
			
			files = $.map(files, function(file,i){
				if($.inArray(encodeURI(file.name), currentfiles) >= 0) {
					return null;
				}
				file.id = CHV.fn.uploader.item_add_id + i;
				CHV.fn.uploader.item_add_id++;
				return file;
			});

			for(var i = 0; i < files.length; i++){
				this.files[files[i].id] = files[i];	
			}

		}
		
		$(this.selectors.queue, this.selectors.root).append(item_queue_template.repeat(files.length));
		
		$(this.selectors.queue + " " + this.selectors.queue_item + ":not([data-id])", this.selectors.root).hide(); // hide the stock items
		
		$(this.selectors.close_cancel, this.selectors.root).hide().each(function() {
			if($(this).data("action") == "close-upload") $(this).show();
		});
		
		var failed_before = failed_files,
			failed_files = [],
			j = 0,
			default_options = {
				canvas: true,
				//maxWidth: 600
			};
		
		$.each(files, function(i){
			var file = files[i];

			$(CHV.fn.uploader.selectors.queue_item + ":not([data-id]) .load-url", CHV.fn.uploader.selectors.queue)[typeof file.url !== "undefined" ? "show" : "remove"]();
			
			loadImage.parseMetaData(file.url ? file.url : file, function(data) {
				
				// Set the queue item placeholder ids
				$(CHV.fn.uploader.selectors.queue_item + ":not([data-id]) .preview:empty", CHV.fn.uploader.selectors.queue).first().closest("li").attr("data-id", file.id);
				
				// Load the image (async)
				loadImage(file.url ? file.url : file, function(img) {
					
					++j;
					
					var $queue_item = $(CHV.fn.uploader.selectors.queue_item + "[data-id="+(file.id)+"]", CHV.fn.uploader.selectors.queue);
					
					if(img.type === "error"/* || typeof data.imageHead == typeof undefined*/) { // image parse error (png always return undefined data)
						failed_files.push({id: file.id, name: file.name.truncate_middle()});
					} else {
						
						if(!$("[data-group=upload-queue]", CHV.fn.uploader.selectors.root).is(":visible")) {
							$("[data-group=upload-queue]", CHV.fn.uploader.selectors.root).css("display", "block");
						}
						
						// Detect true mimetype
						var mimetype = "image/jpeg"; // Default unknown mimetype
						
						if(typeof data.buffer !== typeof undefined) {
							var buffer = (new Uint8Array(data.buffer)).subarray(0, 4);
							var header = "";
							for(var i = 0; i < buffer.length; i++) {
								header += buffer[i].toString(16);
							}
							var header_to_mime = {
								'89504e47': 'image/png',
								'47494638': 'image/gif',
								'ffd8ffe0': 'image/jpeg',
							}
							$.each(['ffd8ffe1', 'ffd8ffe2'], function(i, v) {
								header_to_mime[v] = header_to_mime['ffd8ffe0'];
							});
							if(typeof header_to_mime[header] !== typeof undefined) {
								mimetype = header_to_mime[header];
							}
						}
						
						var title = null;
						if(typeof file.name !== typeof undefined) {
							var basename = PF.fn.baseName(file.name);
							title = $.trim(basename.substring(0, 100).capitalizeFirstLetter().replace(/\.[^/.]+$/g, "").replace(/[\W_]+/g, " "));
						}
						
						// Set source image data						
						CHV.fn.uploader.files[file.id].parsedMeta = {
							title: title,
							width: img.width,
							height: img.height,
							canvas: img, // store source canvas (for client side resizing)
							mimetype: mimetype,
						};
						
						// Resize canvas for better thumb display
						var img = loadImage.scale(img, {maxWidth: 600});

						$queue_item.show();
						
						$("[data-group=upload-queue-ready]", CHV.fn.uploader.selectors.root).show();
						$("[data-group=upload]", CHV.fn.uploader.selectors.root).hide();
							
						$queue_item.find(".load-url").remove();
						$queue_item.find(".preview").removeClass("soft-hidden").show().append(img);
						
						$img = $queue_item.find(".preview").find("img,canvas");
						$img.attr("class", "canvas");
						
						queue_item_h = $queue_item.height();
						queue_item_w = $queue_item.width();
						
						var img_w = parseInt($img.attr("width")) || $img.width();
						var img_h = parseInt($img.attr("height")) || $img.height();
						var img_r = img_w/img_h;
						
						$img.hide();
						
						if(img_w > img_h || img_w == img_h){ // Landscape
							var queue_img_h = img_h < queue_item_h ? img_h : queue_item_h;
							if(img_w > img_h){
								$img.height(queue_img_h).width(queue_img_h*img_r);
							}
						}
						if(img_w < img_h || img_w == img_h){ // Portrait
							var queue_img_w = img_w < queue_item_w ? img_w : queue_item_w;
							if(img_w < img_h){
								$img.width(queue_img_w).height(queue_img_w/img_r);
							}
						}
						if(img_w == img_h) {
							$img.height(queue_img_h).width(queue_img_w);
						}
						
						$img.css({marginTop: - $img.height()/2, marginLeft: - $img.width()/2}).show();

					}
					
					// Last one
					if(j == files.length) {
						
						if(typeof failed_before !== "undefined") {
							failed_files = failed_files.concat(failed_before);
						}

						if(failed_files.length > 0) {
							var failed_message = "";
							for(var i = 0; i < failed_files.length; i++){
								failed_message += "<li>" + failed_files[i].name + "</li>";
								delete CHV.fn.uploader.files[failed_files[i].id];
								$("li[data-id="+ failed_files[i].id +"]", CHV.fn.uploader.selectors.queue).find("[data-action=cancel]").click()
							}
							PF.fn.modal.simple({title: PF.fn._s("Some files couldn't be added"), message: '<ul>'+failed_message+'</ul>'});
						} else {
							CHV.fn.uploader.focus();
						}
						
						CHV.fn.uploader.boxSizer();
					}
					
				}, $.extend({}, default_options, {orientation: data.exif ? data.exif.get("Orientation") : 1}));

			});
			
		});
		
	},
	
	queueSize: function() {
		$("[data-text=queue-objects]", this.selectors.root).text(PF.fn._n("image", "images", Object.size(this.files)));
		$("[data-text=queue-size]", this.selectors.root).text(Object.size(this.files));
	},
	
	queueProgress: function(e) {
		var total_queue_items_done = $("> .completed, > .failed", this.selectors.queue).length,
			total_queue_items = $(this.selectors.queue).children().length,
			total_queueProgress = parseInt(100 * (parseFloat(total_queue_items_done/total_queue_items) + parseFloat((e.loaded / e.total)/total_queue_items)));
			
		$("[data-text=queue-progress]", this.selectors.root).text(total_queueProgress);
	},
	
	upload: function($queue_item) {
		
		var id = $queue_item.data("id"),
			f = this.files[id],
			queue_is_url = typeof f.url !== "undefined";
		
		var source = queue_is_url ? f.url : f;
		var hasForm = typeof f.formValues !== typeof undefined;
		
		if(typeof f == "undefined") {
			if($queue_item.next().exists()) {
				this.upload($queue_item.next());
			}
			return;
		}
		
		$(this.selectors.close_cancel, this.selectors.root).hide().each(function() {
			if($(this).data("action") == "cancel-upload") $(this).show();
		});

		this.is_uploading = true;
		
		// Client side resizing
		if(!queue_is_url && f.parsedMeta.mimetype !== "image/gif" && typeof f.formValues !== typeof undefined && f.formValues.width !== f.parsedMeta.width) {
			isBlob = true;
			var canvas = $("<canvas />")[0];
			canvas.width = f.formValues.width;
			canvas.height = f.formValues.height;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(f.parsedMeta.canvas, 0, 0, canvas.width, canvas.height);
			source = PF.fn.dataURItoBlob(canvas.toDataURL(f.parsedMeta.mimetype));
		};
		
		// HTML5 form
		var form = new FormData();
		var formData = {
			//source: source,
			type: queue_is_url ? "url" : "file",
			action: "upload",
			privacy: $("[data-privacy]", this.selectors.root).first().data("privacy"),
			timestamp: this.timestamp,
			auth_token: PF.obj.config.auth_token,
			category_id: $("[name=upload-category-id]", this.selectors.root).val() || null,
			nsfw: $("[name=upload-nsfw]", this.selectors.root).prop("checked") ? 1 : 0
		};
		if(queue_is_url) {
			formData.source = source;
		}
		if(hasForm) { // Merge with each queue item form data
			$.each(f.formValues, function(i,v) {
				formData[i.replace(/image_/g, "")] = v;
			});
		};
		$.each(formData, function(i,v) {
			form.append(i, v);
		});
		if(typeof formData.source == typeof undefined) {
			form.append("source", source, f.name);
		}
		
		this.files[id].xhr = new XMLHttpRequest();
		
		$queue_item.removeClass("waiting");
		
		if(!queue_is_url) {
			this.files[id].xhr.upload.onprogress = function(e) {
			
				if(e.lengthComputable) {

					CHV.fn.uploader.queueProgress(e);
					
					percentComplete = parseInt((e.loaded / e.total) * 100);
					
					$(CHV.fn.uploader.selectors.item_progress_percent, $queue_item).text(percentComplete);
					$(CHV.fn.uploader.selectors.item_progress_bar, $queue_item).width(100 - percentComplete + "%");
					
					if(percentComplete == 100) {
						$(CHV.fn.uploader.selectors.item_progress_percent, $queue_item).text("");
						CHV.fn.uploader.itemLoading($queue_item);
					}
				}
				
			}
		} else {
			this.queueSize();
			this.queueProgress({loaded: 1, total: 1});
			this.itemLoading($queue_item);
		}
		
		this.files[id].xhr.onreadystatechange = function(){
			
			var is_error = false;
			
			if(this.readyState == 4 && typeof CHV.fn.uploader.files[id].xhr !== "undefined" && CHV.fn.uploader.files[id].xhr.status !== 0) {
				
				$(".loading-indicator", $queue_item).remove();
				$queue_item.removeClass("waiting uploading");
				
				try {
					// Parse the json response
					var JSONresponse = this.responseType !== "json" ? JSON.parse(this.response) : this.response;

					if(typeof JSONresponse !== "undefined" && this.status == 200) {
						$("[data-group=image-link]", $queue_item).attr("href", JSONresponse.image.url_viewer);
					} else {
						if(JSONresponse.error.context == "PDOException") {
							JSONresponse.error.message = "Database error";
						}
						JSONresponse.error.message = CHV.fn.uploader.files[id].name.truncate_middle() + " - " + JSONresponse.error.message;
					}
					
					// Save the server responses
					CHV.fn.uploader.results[this.status == 200 ? "success" : "error"].push(JSONresponse);
					
					if(this.status !== 200) is_error = true;
					
				} catch(err) {		
					
					is_error = true;
					
					var err_handle;
					
					if(typeof JSONresponse == "undefined") {
						// Server epic error
						err_handle = {
							status: 500,
							statusText: "Internal server error"
						}
					} else {
						err_handle = {
							status: 400,
							statusText: JSONresponse.error.message
						}
					}
					
					JSONresponse = {
						status_code: err_handle.status,
						error: {
							message: CHV.fn.uploader.files[id].name.truncate_middle() + " - Server error (" + err_handle.statusText + ")",
							code: err_handle.status,
							context: "XMLHttpRequest"
						},
						status_txt: err_handle.statusText
					};
					
					CHV.fn.uploader.results.error.push(JSONresponse);
					console.log("server error", JSONresponse);
					
				}
				
				$queue_item.addClass(!is_error ? "completed" : "failed");
				
				if(typeof JSONresponse.error !== "undefined" && typeof JSONresponse.error.message !== "undefined") {
					$queue_item.attr("rel", "tooltip").data("tiptip", "top").attr("title", JSONresponse.error.message);
					PF.fn.bindtipTip($queue_item);
				}
				
				if($queue_item.next().exists()) {
					CHV.fn.uploader.upload($queue_item.next());
					$(CHV.fn.uploader.selectors.close_cancel, CHV.fn.uploader.selectors.root).hide().each(function() {
						if($(this).data("action") == "cancel-upload-remaining") $(this).show();
					});
				} else {
					CHV.fn.uploader.is_uploading = false;
					CHV.fn.uploader.displayResults();
				}		
				$(".done", $queue_item).fadeOut();
			}
			
		};
		
		this.files[id].xhr.open("POST", PF.obj.config.json_api, true);
		this.files[id].xhr.setRequestHeader("Accept", "application/json");
		this.files[id].xhr.send(form);
	},
	
	itemLoading: function($queue_item) {
		PF.fn.loading.inline($(".progress", $queue_item), {color: "#FFF", size: "normal", center: true, position: "absolute", shadow: true});
		$("[data-action=cancel], [data-action=edit]", $queue_item).hide();
	},
	
	displayResults: function() {

		var group_result = "[data-group=upload-result][data-result=%RESULT%]",
			result_types = ["error", "mixed", "success"],
			results = {};
		
		for(var i=0; i<result_types.length; i++) {
			results[result_types[i]] = group_result.replace("%RESULT%", result_types[i]);
		}
	
		$("[data-text=queue-progress]", this.selectors.root).text(100);
		$("[data-group=uploading]", this.selectors.root).hide();

		$(this.selectors.close_cancel, this.selectors.root).hide().each(function() {
			if($(this).data("action") == "close-upload") $(this).show();
		});
		
		$(this.selectors.queue).addClass(this.selectors.queue_complete.substring(1));
				
		if(this.results.error.length > 0) {
			var error_files = [];
			for(var i = 0; i < this.results.error.length; i++) {
				error_files.push(this.results.error[i].error.message);
			}
			if(Object.size(error_files) > 0) {
				$(this.selectors.failed_result).html("<li>" + error_files.join("</li><li>") + "</li>");
			}
		} else {
			$(results.error, this.selectors.root).hide();
		}
		
		// Append the embed codes
		if(this.results.success.length > 0 && $("[data-group=upload-result] textarea", this.selectors.root).exists()) {
			CHV.fn.fillEmbedCodes(this.results.success, CHV.fn.uploader.selectors.root, "val");	
		}
		
		if(this.results.success.length > 0 && this.results.error.length > 0) {
			$(results.mixed+", "+results.success, this.selectors.root).show();
		} else if(this.results.success.length > 0) {
			$(results.success, this.selectors.root).show();
		} else if(this.results.error.length > 0) {
			$(results.error, this.selectors.root).show();
		}
		
		if($(results.success, this.selectors.root).is(":visible")) {
			$(results.success, this.selectors.root).find("[data-group=user], [data-group=guest]").hide();
			$(results.success, this.selectors.root).find("[data-group=" + (PF.fn.is_user_logged() ? "user" : "guest") + "]").show();
			if(typeof this.results.success[0].image.album !== "undefined") {
				$("[data-text=upload-target]").text(this.results.success[0].image.album.name);
				$("[data-link=upload-target]").attr("href", this.results.success[0].image.album.url);
			}
		}
		
		this.boxSizer();
		this.queue_status = "done";

	}
	
};

CHV.fn.fillEmbedCodes = function(elements, parent, fn) {
	
	if(typeof fn == "undefined") {
		fn = "val";
	}
	
	$.each(elements, function(key, value) {
				
		var image = value.image;
		
		if(!image.medium) { // Medium doesn't exists
			image.medium = {};
			var imageProp = ["filename", "name", "width", "height", "ratio", "bits", "channels", "extension", "filename", "height", "mime", "name", "ratio", "size", "size_formatted", "url", "width"];
			for(var i=0; i<imageProp.length; i++) {
				image.medium[imageProp[i]] = image[imageProp[i]];
			}
		}
		
		var flatten_image = Object.flatten(image);
		
		$.each(CHV.obj.embed_tpl, function(key,value) {
			$.each(value.options, function(k,v) {
				
				var embed = v,
					$embed = $("textarea[name="+k+"]", parent),
					template = embed.template;
				
				for(var i in flatten_image) {
					if(!flatten_image.hasOwnProperty(i)) {
						continue;
					}

					template = template.replace(new RegExp("%"+i.toUpperCase()+"%", "g"), flatten_image[i]);
				}
				
				$embed[fn]($embed.val() + template + ($embed.data("size") == "thumb" ? " " : "\n"));
				
			});
			
		});

	});
	
	// Remove any extra \n
	$.each(CHV.obj.embed_tpl, function(key,value) {
		$.each(value.options, function(k,v) {
			var $embed = $("textarea[name="+k+"]", parent);
			$embed[fn]($.trim($embed.val()));
		});
	});
	
};

CHV.fn.resource_privacy_toggle = function(privacy) {
	if(!privacy) privacy = "public";
	$("[data-content=privacy-private]").hide();
	if(privacy !== "public") {
		$("[data-content=privacy-private]").show();
	}
};

// Upload edit (move to album or create new)
CHV.fn.submit_upload_edit = function() {
	var $modal = $(PF.obj.modal.selectors.root),
		new_album = false;
	
	if($("[data-content=form-new-album]", $modal).is(":visible") && $("[name=form-album-name]", $modal).val() == "") {
		PF.fn.growl.call(PF.fn._s("You must enter the album name."));
		$("[name=form-album-name]", $modal).highlight();
		return false;
	}
	
	if($("[data-content=form-new-album]", $modal).is(":visible")) {
		new_album = true;
	}
	
	PF.obj.modal.form_data = {
		action: new_album ? "create-album" : "move",
		type: "images",
		album: {
			ids: $.map(CHV.fn.uploader.results.success, function(v) {
				return v.image.id_encoded;
			}),
			new: new_album
		}
	};
	
	if(new_album) {
		PF.obj.modal.form_data.album.name = $("[name=form-album-name]", $modal).val();
		PF.obj.modal.form_data.album.description = $("[name=form-album-description]", $modal).val();
		PF.obj.modal.form_data.album.privacy = $("[name=form-privacy]", $modal).val();
	} else {
		PF.obj.modal.form_data.album.id = $("[name=form-album-id]", $modal).val();
	}
	
	return true;
};
CHV.fn.complete_upload_edit = {
	success: function(XHR) {
		var response = XHR.responseJSON.album;		
		window.location = response.url;
	},
	error: function(XHR) {
		var response = XHR.responseJSON;
		PF.fn.growl.call(PF.fn._s(response.error.message));
	}
};

// Image edit
CHV.fn.before_image_edit = function() {
	var $modal = $("[data-ajax-deferred='CHV.fn.complete_image_edit']");
	$("[data-content=form-new-album]", $modal).hide();
	$("#move-existing-album", $modal).show();
};
CHV.fn.submit_image_edit = function() {
	
	var $modal = $(PF.obj.modal.selectors.root),
		new_album = false;
	
	if($("[data-content=form-new-album]", $modal).is(":visible") && $("[name=form-album-name]", $modal).val() == "") {
		PF.fn.growl.call(PF.fn._s("You must enter the album name."));
		$("[name=form-album-name]", $modal).highlight();
		return false;
	}
	
	if($("[data-content=form-new-album]", $modal).is(":visible")) {
		new_album = true;
	}
	
	PF.obj.modal.form_data = {
		action: "edit",
		edit: "image",
		editing: {
			id: CHV.obj.resource.id,
			category_id: $("[name=form-category-id]", $modal).val() || null,
			title: $("[name=form-image-title]", $modal).val() || null,
			description: $("[name=form-image-description]", $modal).val() || null,
			nsfw: $("[name=form-nsfw]", $modal).prop("checked") ? 1 : 0,
			new_album: new_album
		}
	};
	
	if(new_album) {
		PF.obj.modal.form_data.editing.album_privacy = $("[name=form-privacy]", $modal).val();
		PF.obj.modal.form_data.editing.album_name = $("[name=form-album-name]", $modal).val();
		PF.obj.modal.form_data.editing.album_description = $("[name=form-album-description]", $modal).val();
	} else {
		PF.obj.modal.form_data.editing.album_id = $("[name=form-album-id]", $modal).val();
	}
	
	return true;
	
};
CHV.fn.complete_image_edit = {
	success: function(XHR) {
	
		var response = XHR.responseJSON.image;
		
		if(!response.album.id_encoded) response.album.id_encoded = "";
		
		// Detect album change
		if(CHV.obj.image_viewer.album.id_encoded !== response.album.id_encoded) {
			
			CHV.obj.image_viewer.album.id_encoded = response.album.id_encoded;
			
			var slice = {
				html: response.album.slice && response.album.slice.html ? response.album.slice.html : null,
				prev: response.album.slice && response.album.slice.prev ? response.album.slice.prev : null,
				next: response.album.slice && response.album.slice.next ? response.album.slice.next : null
			};
			
			$("[data-content=album-slice]").html(slice.html);
			$("[data-content=album-panel-title]")[slice.html ? "show" : "hide"]();

			$("a[data-action=prev]").attr("href", slice.prev);
			$("a[data-action=next]").attr("href", slice.next);
			
			$("a[data-action]", ".image-viewer-navigation").each(function(){
				$(this)[typeof $(this).attr("href") == "undefined" ? "addClass" : "removeClass"]("hidden");
			});
			
		}
		
		CHV.fn.resource_privacy_toggle(response.album.privacy);
		
		$.each(["description", "title"], function(i,v) {
			var $obj = $("[data-text=image-"+ v +"]");
			$obj.html(PF.fn.nl2br(PF.fn.htmlEncode(response[v])));
			if($obj.html() !== "") {
				$obj.show();
			}
		});
		
		CHV.fn.common.updateDoctitle(response.title);
		
		PF.fn.growl.expirable(PF.fn._s("Image edited successfully."));
		
		// Add album to modals
		CHV.fn.list_editor.addAlbumtoModals(response.album);
		
		// Reset modal
		var $modal = $("[data-submit-fn='CHV.fn.submit_image_edit']");
				
		$("[name=form-album-name]", $modal).val("").attr("value", "");
		$("[name=form-privacy]", $modal).find("option").removeAttr("selected");
		
		// Select the album
		$("[name=form-album-id]", $modal).find("option").removeAttr("selected");
		$("[name=form-album-id]", $modal).find("[value="+response.album.id_encoded+"]").attr("selected", true);
		
	}
};

// Album edit
CHV.fn.before_album_edit = function(e) {
	var modal_source = "[data-before-fn='CHV.fn.before_album_edit']";
	$("[data-action=album-switch]", modal_source).remove();
	
};
CHV.fn.submit_album_edit = function() {
	var $modal = $(PF.obj.modal.selectors.root);
	
	if(!$("[name=form-album-name]", $modal).val()) {
		PF.fn.growl.call(PF.fn._s("You must enter the album name."));
		$("[name=form-album-name]", $modal).highlight();
		return false;
	}
	
	PF.obj.modal.form_data = {
		action: "edit",
		edit: "album",
		editing: {
			id: CHV.obj.resource.id,
			name: $("[name=form-album-name]", $modal).val(),
			privacy: $("[name=form-privacy]", $modal).val(),
			description: $("[name=form-album-description]", $modal).val()
		}
	};
	
	return true;
	
};
CHV.fn.complete_album_edit = {

	success: function(XHR) {
	
		var album = XHR.responseJSON.album;
		
		$("[data-text=album-name]").html(PF.fn.htmlEncode(album.name));
		$("[data-text=album-description]").html(PF.fn.htmlEncode(album.description));
		CHV.fn.resource_privacy_toggle(album.privacy);
		
		var stock = CHV.obj.resource.type;
		CHV.obj.resource.type = null;
		CHV.fn.list_editor.updateItem($(".list-item"), XHR.responseJSON);
		CHV.obj.resource.type = stock;
		
		$("[data-modal]").each(function(){
			$("option[value="+album.id_encoded+"]", this).text(album.name + (album.privacy !== "public" ? ' ('+PF.fn._s("private")+')' : ''));
		});
		
		CHV.fn.common.updateDoctitle(album.name);
		
		PF.fn.growl.expirable(PF.fn._s("Album edited successfully."));

	}
};

// Category edit
CHV.fn.category = {
	formFields: ["id", "name", "url_key", "description"],
	validateForm: function(id) {
		var modal = PF.obj.modal.selectors.root,
			submit = true,
			used_url_key = false;
		
		if(!CHV.fn.common.validateForm(modal)) {
			return false;
		}
		
		if(/^[-\w]+$/.test($("[name=form-category-url_key]", modal).val()) == false) {
			PF.fn.growl.call(PF.fn._s("Invalid URL key."));
			$("[name=form-category-url_key]", modal).highlight();
			return false;
		}
		
		if(Object.size(CHV.obj.categories) > 0) {
			$.each(CHV.obj.categories, function(i,v){
				if(typeof id !== "undefined" && v.id == id) return true;
				if(v.url_key == $("[name=form-category-url_key]", modal).val()) {
					used_url_key = true;
					return false;
				}
			});
		}
		if(used_url_key) {
			PF.fn.growl.call(PF.fn._s("Category URL key already being used."));
			$("[name=form-category-url_key]", modal).highlight();
			return false;
		}
		
		return true;
	},
	edit: {
		before: function(e) {
			var $this = $(e.target),
				id = $this.data("category-id"),
				category = CHV.obj.categories[id],
				modal_source = "[data-modal=" + $this.data("target") + "]";
			$.each(CHV.fn.category.formFields , function(i, v) {
				var i = "form-category-" + v,
					v = category[v],
					$input = $("[name=" + i +"]", modal_source);
				if($input.is("textarea")) {
					$input.html(PF.fn.htmlEncode(v));
				} else {
					$input.attr("value", v);
				}
			});
		},
		submit: function() {
			var modal = PF.obj.modal.selectors.root,
				id = $("[name=form-category-id]", modal).val();
			
			if(!CHV.fn.category.validateForm(id)) {
				return false;
			}
			
			PF.obj.modal.form_data = {
				action: "edit",
				edit: "category",
				editing: {}
			};
			$.each(CHV.fn.category.formFields, function(i,v) {
				PF.obj.modal.form_data.editing[v] = $("[name=form-category-"+v+"]", modal).val();
			});
			
			return true;
		},
		complete: {
			success: function(XHR) {
				var category = XHR.responseJSON.category,
					parent = "[data-content=category][data-category-id=" + category.id + "]";
					
				$.each(category, function(i,v) {
					$("[data-content=category-" + i + "]", parent).html(PF.fn.htmlEncode(v));
				});
				
				$("[data-link=category-url]").attr("href", category.url);
				
				CHV.obj.categories[category.id] = category;
				
			}
		}
	},
	delete: {
		before: function(e) {
			var $this = $(e.target),
				id = $this.data("category-id"),
				category = CHV.obj.categories[id];
				$this.attr("data-confirm", $this.attr("data-confirm").replace("%s", '"' + category.name + '"'));
		},
		submit: function(id) {
			PF.obj.modal.form_data = {
				action: "delete",
				delete: "category",
				deleting: {
					id: id
				}
			};
			return true;
		},
		complete: {
			success: function(XHR) {
				PF.fn.growl.expirable(PF.fn._s("Category successfully deleted."));
				var id = XHR.responseJSON.request.deleting.id;
				$("[data-content=category][data-category-id=" + id + "]").remove();
				
				delete CHV.obj.categories[id];
			}
		}
	},
	add: {
		submit: function() {
			
			var modal = PF.obj.modal.selectors.root;
			
			if(!CHV.fn.category.validateForm()) {
				return false;
			}
			
			PF.obj.modal.form_data = {
				action: "add-category",
				category: {}
			};
			$.each(CHV.fn.category.formFields, function(i,v) {
				if(v=="id") return;
				PF.obj.modal.form_data.category[v] = $("[name=form-category-"+v+"]", modal).val();
			});
			
			return true;
		},
		complete: {
			success: function(XHR) {
				var category = XHR.responseJSON.category,
					list = "[data-content=dashboard-categories-list]",
					html = $("[data-content=category-dashboard-template]").html(),
					replaces = {};
				
				$.each(category, function(i,v) {
					html = html.replace(new RegExp("%" + i.toUpperCase() + "%", "g"), v ? v : "");
				});
				
				$(list).append(html);
				
				if(Object.size(CHV.obj.categories) == 0) {
					CHV.obj.categories = {};
				}
				CHV.obj.categories[category.id] = category;
				
				PF.fn.growl.call(PF.fn._s("Category %s added.", '"'+ category.name + '"'));
			}
		}
	}
};

// IP ban edit
CHV.fn.ip_ban = {
	formFields: ["id", "ip", "expires", "message"],
	validateForm: function(id) {

		var modal = PF.obj.modal.selectors.root,
			submit = true,
			already_banned = false,
			ip = $("[name=form-ip_ban-ip]", modal).val();
		
		if(!CHV.fn.common.validateForm(modal)) {
			return false;
		}
		
		if($("[name=form-ip_ban-expires]", modal).val() !== "" && /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test($("[name=form-ip_ban-expires]", modal).val()) == false) {
			PF.fn.growl.call(PF.fn._s("Invalid expiration date."));
			$("[name=form-ip_ban-expires]", modal).highlight();
			return false;
		}
		
		if(Object.size(CHV.obj.ip_bans) > 0) {
			$.each(CHV.obj.ip_bans, function(i,v){
				if(typeof id !== "undefined" && v.id == id) return true;
				if(v.ip == ip) {
					already_banned = true;
					return false;
				}
			});
		}
		if(already_banned) {
			PF.fn.growl.call(PF.fn._s("IP %s already banned.", ip));
			$("[name=form-ip_ban-ip]", modal).highlight();
			return false;
		}
		
		return true;
	},
	
	add: {
		submit: function() {
			
			var modal = PF.obj.modal.selectors.root;
			
			if(!CHV.fn.ip_ban.validateForm()) {
				return false;
			}
			
			PF.obj.modal.form_data = {
				action: "add-ip_ban",
				ip_ban: {}
			};
			$.each(CHV.fn.ip_ban.formFields, function(i,v) {
				if(v=="id") return;
				PF.obj.modal.form_data.ip_ban[v] = $("[name=form-ip_ban-"+v+"]", modal).val();
			});
			
			return true;
		},
		complete: {
			success: function(XHR) {
			
				var ip_ban = XHR.responseJSON.ip_ban,
					list = "[data-content=dashboard-ip_bans-list]",
					html = $("[data-content=ip_ban-dashboard-template]").html(),
					replaces = {};
				
				if(typeof html !== "undefined") {
					$.each(ip_ban, function(i,v) {
						html = html.replace(new RegExp("%" + i.toUpperCase() + "%", "g"), v ? v : "");
					});
					$(list).append(html);
				}
				
				if(Object.size(CHV.obj.ip_bans) == 0) {
					CHV.obj.ip_bans = {};
				}
				CHV.obj.ip_bans[ip_ban.id] = ip_ban;
				
				$("[data-content=ban_uploader_ip]").hide();
				$("[data-content=banned_uploader_ip]").show();
				
				PF.fn.growl.call(PF.fn._s("IP %s banned.", ip_ban.ip));
				
			},
			error: function(XHR) { // experimental				
				var error = XHR.responseJSON.error;
				PF.fn.growl.call(PF.fn._s(error.message));
			}
		}
	},
	
	edit: {
		before: function(e) {
			var $this = $(e.target),
				id = $this.data("ip_ban-id"),
				target = CHV.obj.ip_bans[id],
				modal_source = "[data-modal=" + $this.data("target") + "]";
			$.each(CHV.fn.ip_ban.formFields , function(i, v) {
				var i = "form-ip_ban-" + v,
					v = target[v],
					$input = $("[name=" + i +"]", modal_source);
				if($input.is("textarea")) {
					$input.html(PF.fn.htmlEncode(v));
				} else {
					$input.attr("value", v);
				}
			});
		},
		submit: function() {
			var modal = PF.obj.modal.selectors.root,
				id = $("[name=form-ip_ban-id]", modal).val();
			
			if(!CHV.fn.ip_ban.validateForm(id)) {
				return false;
			}
			
			PF.obj.modal.form_data = {
				action: "edit",
				edit: "ip_ban",
				editing: {}
			};
			$.each(CHV.fn.ip_ban.formFields, function(i,v) {
				PF.obj.modal.form_data.editing[v] = $("[name=form-ip_ban-"+v+"]", modal).val();
			});
			
			return true;
		},
		complete: {
			success: function(XHR) {
				var ip_ban = XHR.responseJSON.ip_ban,
					parent = "[data-content=ip_ban][data-ip_ban-id=" + ip_ban.id + "]";
				
				$.each(ip_ban, function(i,v) {
					$("[data-content=ip_ban-" + i + "]", parent).html(PF.fn.htmlEncode(v));
				});
				
				CHV.obj.ip_bans[ip_ban.id] = ip_ban;
				
			}
		}
	},
	
	delete: {
		before: function(e) {
			var $this = $(e.target),
				id = $this.data("ip_ban-id"),
				ip_ban = CHV.obj.ip_bans[id];
				$this.attr("data-confirm", $this.attr("data-confirm").replace("%s", ip_ban.ip));
		},
		submit: function(id) {
			PF.obj.modal.form_data = {
				action: "delete",
				delete: "ip_ban",
				deleting: {
					id: id
				}
			};
			return true;
		},
		complete: {
			success: function(XHR) {
				PF.fn.growl.expirable(PF.fn._s("IP ban successfully deleted."));
				var id = XHR.responseJSON.request.deleting.id;
				$("[data-content=ip_ban][data-ip_ban-id=" + id + "]").remove();
				
				delete CHV.obj.ip_bans[id];
			}
		}
	}
};

// Storage edit
CHV.fn.storage = {
	formFields: ["id", "name", "api_id", "bucket", "server", "capacity", "region", "key", "secret", "url", "account_id", "account_name"],
	calling: false,
	validateForm: function() {

		var modal = PF.obj.modal.selectors.root,
			id = $("[name=form-storage-id]", modal).val(),
			submit = true;
		
		$.each($(":input", modal), function(i,v) {
			if($(this).is(":hidden")) {
				if($(this).attr("required")) {
					$(this).removeAttr("required").attr("data-required", 1);
				}
			} else {
				if($(this).attr("data-required") == 1) {
					$(this).attr("required", "required");
				}
			}
			if($(this).is(":visible") && $(this).val() == "" && $(this).attr("required")) {
				$(this).highlight();
				submit = false;
			}
		});
		
		if(!submit) {
			PF.fn.growl.call(PF.fn._s("Please fill all the required fields."));
			return false;
		}
		
		// Validate storage capacity
		var $storage_capacity = $("[name=form-storage-capacity]", modal),
			storage_capacity = $storage_capacity.val(),
			capacity_error_msg;
			
		if(storage_capacity !== "") {
			if(/^[\d\.]+\s*[A-Za-z]{2}$/.test(storage_capacity) == false || typeof storage_capacity.getBytes() == "undefined") {
				capacity_error_msg = PF.fn._s("Invalid storage capacity value. Make sure to use a valid format.");
			} else if(typeof CHV.obj.storages[id] !== "undefined" && storage_capacity.getBytes() < CHV.obj.storages[id].space_used) {
				capacity_error_msg = PF.fn._s("Storage capacity can't be lower than its current usage (%s).", CHV.obj.storages[id].space_used.formatBytes());
			}
			if(capacity_error_msg) {
				PF.fn.growl.call(capacity_error_msg);
				$storage_capacity.highlight();
				return false;
			}
		}
		
		if(/^https?:\/\/.+$/.test($("[name=form-storage-url]", modal).val()) == false) {
			PF.fn.growl.call(PF.fn._s("Invalid URL."));
			$("[name=form-storage-url]", modal).highlight();
			return false;
		}
		return true;
	},
	toggleHttps: function(id) {
		this.toggleBool(id, "https");
	},
	toggleActive: function(id) {
		this.toggleBool(id, "active");
	},
	toggleBool: function(id, string) {
		
		if(this.calling) return;
		
		this.calling = true;
		
		var $root = $("[data-storage-id="+id+"]"),
			$parent = $("[data-content=storage-" + string + "]", $root),
			$el = $("[data-checkbox]", $parent),
			checked = CHV.obj.storages[id]["is_" + string],
			toggle = checked == 0 ? 1 : 0,
			data = {
				action: "edit",
				edit: "storage",
				editing: {
					id: id
				}
			};
			data.editing["is_" + string] = toggle;
			if(string == "https") {
				data.editing.url = CHV.obj.storages[id].url;
			}
		
		PF.fn.loading.fullscreen();
		
		$.ajax({data: data})
			.always(function(data, status, XHR) {
				
				CHV.fn.storage.calling = false;
				PF.fn.loading.destroy("fullscreen");
				
				if(typeof data.storage == "undefined") {
					PF.fn.growl.call(data.responseJSON.error.message);
					return;
				}
				
				var storage = data.storage;
				CHV.obj.storages[storage.id] = storage;
				
				PF.fn.growl.expirable(PF.fn._s("Storage successfully edited."));
				
				switch(string) {
					case "https":
						$("[data-content=storage-url]", $root).html(storage.url);
					break;
				}
				
				CHV.fn.storage.toggleBoolDisplay($el, toggle);	
				
				CHV.fn.queuePixel(); // For the lulz
			});
	},
	edit: {
		before: function(e) {
			var $this = $(e.target),
				id = $this.data("storage-id"),
				storage = CHV.obj.storages[id],
				modal_source = "[data-modal=" + $this.data("target") + "]",
				combo = "[data-combo-value~=" + storage['api_id'] + "]";
			
			$.each(CHV.fn.storage.formFields, function(i, v) {
				var i = "form-storage-" + v,
					v = storage[v],
					$combo_input = $(combo + " [name=" + i +"]", modal_source),
					$global_input = $("[name=" + i +"]", modal_source),
					$input = $combo_input.exists() ? $combo_input : $global_input;
				if($input.is("textarea")) {
					$input.html(PF.fn.htmlEncode(v));
				} else if($input.is("select")) {
					$("option", $input).removeAttr("selected");
					$("option", $input).each(function() {
						if($(this).attr("value") == v) {
							$(this).attr("selected", "selected");
							return false;
						}
					});
				} else {
					if($input.is("[name=form-storage-capacity]") && typeof v !== "undefined" && v > 0) {
						v = v.formatBytes(2);
					}
					$input.attr("value", v);
				}
			});
			
			// Co-combo breaker
			$("[data-combo-value]").addClass("soft-hidden");
			$(combo).removeClass("soft-hidden");
			
		},
		submit: function() {
			var modal = PF.obj.modal.selectors.root,
				id = $("[name=form-storage-id]", modal).val(),
				used_url_key = false;
			
			if(!CHV.fn.storage.validateForm()) {
				return false;
			}
			
			PF.obj.modal.form_data = {
				action: "edit",
				edit: "storage",
				editing: {}
			};
			$.each(CHV.fn.storage.formFields, function(i,v) {
				var sel;
				sel = "[name=form-storage-"+v+"]";
				if($(sel, modal).attr("type") !== "hidden") {
					sel += ":visible"
				}
				PF.obj.modal.form_data.editing[v] = $(sel, modal).val();
			});
			
			return true;
			
		},
		complete: {
			success: function(XHR) {
				var	storage = XHR.responseJSON.storage,
					parent = "[data-content=storage][data-storage-id=" + storage.id + "]",
					$el = $("[data-action=toggle-storage-https]", parent);
				$.each(storage, function(i,v) {
					$("[data-content=storage-" + i + "]", parent).html(PF.fn.htmlEncode(v));
				});
				CHV.obj.storages[storage.id] = storage;
				CHV.fn.storage.toggleBoolDisplay($el, storage['is_https'] == 1);
				CHV.fn.queuePixel(); // For the lulz
			},
			error: function(XHR) {
				var response = XHR.responseJSON,
					message = response.error.message;
				PF.fn.growl.call(message);
			}
		}
	},
	add: {
		submit: function() {
			if(!CHV.fn.storage.validateForm()) {
				return false;
			}
			var modal = PF.obj.modal.selectors.root;
			
			PF.obj.modal.form_data = {
				action: "add-storage",
				storage: {}
			};
			$.each(CHV.fn.storage.formFields, function(i,v) {
				if(v=="id") return;
				var sel;
				sel = "[name=form-storage-"+v+"]";
				if($(sel, modal).attr("type") !== "hidden") {
					sel += ":visible"
				}
				PF.obj.modal.form_data.storage[v] = $(sel, modal).val();
			});
			
			return true;
		},
		complete: {
			success: function(XHR) {
				var storage = XHR.responseJSON.storage,
					list = "[data-content=dashboard-storages-list]",
					html = $("[data-content=storage-dashboard-template]").html(),
					replaces = {};
				
				$.each(storage, function(i,v) {
					var upper = i.toUpperCase();
					if(i == "is_https" || i == "is_active") {
						var v = CHV.obj.storageTemplate.icon.replace("%TITLE%", CHV.obj.storageTemplate.messages[i]).replace("%ICON%", CHV.obj.storageTemplate.checkboxes[v]).replace("%PROP%", i.replace("is_", ""));
					}
					html = html.replace(new RegExp("%" + upper + "%", "g"), v ? v : "");
				});

				$(list).append(html);
				
				PF.fn.bindtipTip($("[data-storage-id="+storage.id+"]"));
				
				if(CHV.obj.storages.length == 0) {
					CHV.obj.storages = {};
				}
				CHV.obj.storages[storage.id] = storage;
				
				CHV.fn.queuePixel(); // For the lulz
				
			},
			error: function(XHR) {
				var response = XHR.responseJSON,
					message = response.error.message;
				PF.fn.growl.call(message);
			}
		}
	},
	toggleBoolDisplay: function($el, toggle) {
		var icons = {
				0: $el.data("unchecked-icon"),
				1: $el.data("checked-icon")
			};
		$el.removeClass(icons[0] + " " + icons[1]).addClass(icons[toggle ? 1 : 0]);
	}
};

CHV.fn.common = {
	validateForm: function(modal) {
		if(typeof modal == "undefined") {
			var modal = PF.obj.modal.selectors.root
		}
		
		var submit = true;
		
		$.each($(":input:visible", modal), function(i,v) {
			if($(this).val() == "" && $(this).attr("required")) {
				$(this).highlight();
				submit = false;
			}
		});
		if(!submit) {
			PF.fn.growl.call(PF.fn._s("Please fill all the required fields."));
			return false;
		}
		
		return true;
	},
	updateDoctitle: function(pre_doctitle) {
		if(typeof CHV.obj.page_info !== typeof undefined) {
			CHV.obj.page_info.pre_doctitle = pre_doctitle;
			CHV.obj.page_info.doctitle = CHV.obj.page_info.pre_doctitle + CHV.obj.page_info.pos_doctitle;
			document.title = CHV.obj.page_info.doctitle;
		}
	}
};

CHV.fn.user = {
	add: {
		submit: function() {
			var $modal = $(PF.obj.modal.selectors.root),
				submit = true;
			
			$.each($(":input", $modal), function(i,v) {
				if($(this).val() == "" && $(this).attr("required")) {
					$(this).highlight();
					submit = false;
				}
			});
			
			if(!submit) {
				PF.fn.growl.call(PF.fn._s("Please fill all the required fields."));
				return false;
			}
			
			PF.obj.modal.form_data = {
				action: "add-user",
				user: {
					username: $("[name=form-username]", $modal).val(),
					email: $("[name=form-email]", $modal).val(),
					password: $("[name=form-password]", $modal).val(),
					role: $("[name=form-role]", $modal).val()
				}
			};
			
			return true;
		},
		complete: {
			success: function(XHR) {
				var response = XHR.responseJSON;
				PF.fn.growl.expirable(PF.fn._s("User added successfully."));
			},
			error: function(XHR) {
				var response = XHR.responseJSON;
				PF.fn.growl.call(PF.fn._s(response.error.message));
			}
		}
	},
	delete: {
		submit: function() {
			PF.obj.modal.form_data = {
				action: "delete",
				delete: "user",
				owner: CHV.obj.resource.user.id,
				deleting: CHV.obj.resource.user
			};
			return true;
		}
	}
};

// Resource delete
CHV.fn.submit_resource_delete = function() {
	PF.obj.modal.form_data = {
		action: "delete",
		delete: CHV.obj.resource.type,
		from: "resource",
		owner: typeof CHV.obj.resource.user !== "undefined" ? CHV.obj.resource.user.id : null,
		deleting: CHV.obj.resource
	};
	return true;
};
CHV.fn.complete_resource_delete = {
	success: function(XHR) {
		var response = XHR.responseJSON;
		$("body").fadeOut("normal", function() {
			var redir;
			if(CHV.obj.resource.type == "album" || CHV.obj.resource.type == "image") {
				redir = CHV.obj.resource.parent_url;
			} else {
				redir = CHV.obj.resource.user ? CHV.obj.resource.user.url : CHV.obj.resource.url;
			}
			if(typeof redir !== "undefined") {
				window.location = redir + "?deleted";
			}
		});
	}
};

CHV.fn.list_editor = {

	// Update all the selection counts
	selectionCount: function() {

		var $content_listing = $(PF.obj.listing.selectors.content_listing);
		
		$content_listing.each(function() {
			
			var $listing_options = $("[data-content=pop-selection]", "[data-content=list-selection][data-tab=" + $(this).attr("id") + "]"),
				selection_count = $(PF.obj.listing.selectors.list_item+".selected", this).length;
				all_count = $(PF.obj.listing.selectors.list_item, this).length;
			
			$listing_options[selection_count > 0 ? "removeClass" : "addClass"]("disabled");
			$("[data-text=selection-count]", $listing_options).text(selection_count > 0 ? selection_count : "");
			
			// Sensitive display
			if($content_listing.data('list') == 'images' && selection_count > 0) {
				var has_sfw = $(PF.obj.listing.selectors.list_item+".selected[data-flag=safe]", this).length > 0,
					has_nsfw = $(PF.obj.listing.selectors.list_item+".selected[data-flag=unsafe]", this).length > 0;
				$("[data-action=flag-safe]", $listing_options)[(has_nsfw ? "remove" : "add") + "Class"]("hidden");
				$("[data-action=flag-unsafe]", $listing_options)[(has_sfw ? "remove" : "add") + "Class"]("hidden");
			}
			
			if($(this).is(":visible")) {
				CHV.fn.list_editor.listMassActionSet(all_count == selection_count ? "clear" : "select");
			}
		});
		
	},
	
	// Remove (delete or move) items from list
	removeFromList: function($target, msg) {
		
		if(typeof $target == "undefined") return;
		
		var $target = $target instanceof jQuery == false ? $($target) : $target,
			$content_listing = $(PF.obj.listing.selectors.content_listing_visible),
			target_size = $target.length;
		
		$target.fadeOut("fast"); // Promise

		// Update counts
		var type = $target.first().data("type"),
			new_count = parseInt($("[data-text="+type+"-count]").text()) - target_size;

		CHV.fn.list_editor.updateUserCounters($target.first().data("type"), target_size, "-");

		$target.promise().done(function() {
			
			// Get count related to each list
			var affected_content_lists = {};
			$target.each(function() {
				$("[data-id="+$(this).data("id")+"]").each(function(){
					var list_id = $(this).closest(PF.obj.listing.selectors.content_listing).attr("id");
				
					if(!affected_content_lists[list_id]) {
						affected_content_lists[list_id] = 0;
					}
					
					affected_content_lists[list_id] += 1;
				});
			});
			
			if(target_size == 1) {
				$("[data-id="+$(this).data("id")+"]").remove();
			} else {
				$target.each(function(){
					$("[data-id="+$(this).data("id")+"]").remove();
				});
			}
			
			PF.fn.listing.columnizerQueue();
			PF.fn.listing.refresh();
			
			CHV.fn.list_editor.selectionCount();
			
			if(typeof msg !== "undefined" && typeof msg == "string") {
				PF.fn.growl.expirable(msg);
			}
			
			// Update offset list (+stock)
			for(var k in affected_content_lists) {
				var $list = $("#"+k),
					stock_offset = $list.data("offset"),
					offset = - affected_content_lists[k];;
				
				stock_offset = (typeof stock_offset == "undefined") ? 0 : parseInt(stock_offset);
				
				$list.data("offset", stock_offset + offset);
			}
			
			if(!$(PF.obj.listing.selectors.content_listing_pagination, $content_listing).exists() && $(".list-item", $content_listing).length == 0) {
				new_count = 0;
			}
			
			// On zero add the empty template
			if(new_count == 0) {
				$content_listing.html(PF.obj.listing.template.empty);
				// Reset ajaxed status of all
				$(PF.obj.listing.selectors.content_listing+":not("+PF.obj.listing.selectors.content_listing_visible+")").data({empty: null, load: "ajax"});
				$("[data-content=list-selection][data-tab="+$content_listing.attr("id")+"]").addClass("disabled");
			} else {
				// Count isn't zero.. But the view?
				if($(PF.obj.listing.selectors.list_item, $content_listing).length == 0) {
					$(PF.obj.listing.selectors.pad_content).height(0);
					$content_listing.find("[data-action=load-more]").click();
					PF.obj.listing.recolumnize = true;
				}
				
			}
			
		});
	},
	
	deleteFromList: function($target) {
		if(typeof growl == "undefined") {
			var growl = true;
		}
		var $target = $target instanceof jQuery == false ? $($target) : $target;
		this.removeFromList($target, growl ? PF.fn._s("The content has been deleted.") : null);	
	},
	
	moveFromList: function($target, growl) {
		if(typeof growl == "undefined") {
			var growl = true;
		}
		var $target = $target instanceof jQuery == false ? $($target) : $target;
		this.removeFromList($target, growl ? PF.fn._s("The content has been moved.") : null);
	},
	
	toggleSelectItem: function($list_item, select) {
		if(typeof select !== "boolean") {
			var select = true;
		}
		var $icon = $("[data-action=select] .btn-icon", $list_item),
			add_class, remove_class, label_text;

		if(!select){
			$list_item.removeClass("selected").find(".list-item-image-tools").css("display", "none");
			add_class = $icon.data("icon-unselected");
			remove_class = $icon.data("icon-selected");
			label_text = PF.fn._s("Select");
			setTimeout(function() { // Nifty hack to prevent flicker
				  $list_item.find(".list-item-image-tools").css("display", "");
			}, 0);
		} else {
			$list_item.addClass("selected");
			add_class = $icon.data("icon-selected");
			remove_class = $icon.data("icon-unselected");
			label_text = PF.fn._s("Unselect");
		}
		
		$("[data-action=select] .label", $list_item).text(label_text);
		$icon.removeClass(remove_class).addClass(add_class);

		CHV.fn.list_editor.selectionCount();
	},
	selectItem: function($list_item) {
		this.toggleSelectItem($list_item, true);
	},
	unselectItem: function($list_item) {
		this.toggleSelectItem($list_item, false);
	},
	
	clearSelection: function(all) {
		var $targets = $(PF.obj.listing.selectors.list_item+".selected", PF.obj.listing.selectors[all ? "content_listing" : "content_listing_visible"]);
		this.unselectItem($targets);
		this.listMassActionSet("select");
	},
	
	listMassActionSet: function(action) {
		var current = action == "select" ? "clear" : "select";
		var $target = $("[data-action=list-" + current + "-all]:visible");
		var text = $target.data("text-" + action + "-all");
		$target.text(text).attr("data-action", "list-" + action + "-all");
	},
	
	updateItem: function($target, response, action, growl) {
		if($target instanceof jQuery == false) {
			var $target = $($target);
		}
		
		var dealing_with = $target.data("type"),
			album = dealing_with == "image" ? response.album : response;
		
		this.addAlbumtoModals(album);
		
		$("option[value="+album.id_encoded+"]","[name=form-album-id]").html(PF.fn.htmlEncode(album.name));
		
		if(typeof action == "undefined") {
			var action = "edit";
		}
		
		if(action == "edit" || action == "move") {
			if(action == "move" && CHV.obj.resource.type == "album") {
				CHV.fn.list_editor.moveFromList($target, growl);
				return
			}
			$target.data("description", response.description);
			
			
			
			
			if(dealing_with == "image") {
				$target.data("title", response.title);
				$target.find("[title]").attr("title", response.title);
				$target.data("category-id", response.category_id);
				$target.data({"album-id": album.id_encoded, flag: response.nsfw == 1 ? "unsafe" : "safe"}).removeClass("safe unsafe").addClass(response.nsfw == 1 ? "unsafe" : "safe");
				$("[data-content=album-link]", $target).attr("href", album.url);
				$("[data-text=image-title]", $target).html(PF.fn.htmlEncode(response.title));
				$("[data-text=image-title-truncated]", $target).html(PF.fn.htmlEncode(response.title_truncated));
			} else {
				$target.data("privacy", album.privacy);
				$target.data("name", album.name);
			}
			$target.removeClass("privacy-public privacy-private").addClass("privacy-" + album.privacy);
			$("[data-text=album-name]", $target).html(PF.fn.htmlEncode(album.name));
			
			PF.fn.growl.expirable(action == "edit" ? PF.fn._s("The content has been edited.") : PF.fn._s("The content has been moved."));
		}		
	},

	addAlbumtoModals: function(album) {
		var added = false;
		$("[name=form-album-id]", "[data-modal]").each(function(){
			if(album.id_encoded && !$("option[value=" + album.id_encoded + "]", this).exists()) {
				$(this).append('<option value="'+ album.id_encoded +'">'+ album.name + (album.privacy !== "public" ? ' ('+PF.fn._s("private")+')' : '') + '</option>');
				added = true;
			}
		});
		if(added) {
			CHV.fn.list_editor.updateUserCounters("album", 1, "+");
		}
	},
	
	updateAlbum: function(album) {
		$("[data-id="+album.id_encoded+"]").each(function() {
			if(album.html !== "") {
				$(this).after(album.html);
				$(this).remove();
			}
		});
	},
	
	updateUserCounters: function(counter, number, operation) {

		if(typeof operation == "undefined") {
			var operation = "+";
		}
		
		// Current resource counter
		var $count = $("[data-text="+counter+"-count]"),
			$count_label = $("[data-text="+counter+"-label]"),
			number = parseInt(number),
			old_count = parseInt($count.html()),
			new_count,
			delta;
		
		switch(operation) {
			case "+":
				new_count = old_count + number;
			break;
			case "-":
				new_count = old_count - number;
			break;
			case "=":
				new_count = number;
			break;
		}
		
		delta = new_count - old_count;
		
		// Total counter
		var $total_count = $("[data-text=total-"+$count.data("text")+"]"),
			$total_count_label = $("[data-text="+$total_count.data("text")+"-label]"),
			old_total_count = parseInt($total_count.html()),
			new_total_count = old_total_count + delta;
		
		$count.text(new_count);
		$total_count.text(new_total_count);
		$count_label.text($count_label.data(new_count == 1 ? "label-single" : "label-plural"));
		$total_count_label.text($count_label.data(new_total_count == 1 ? "label-single" : "label-plural"));
		
	},
	
	updateMoveItemLists: function(response, dealing_with, $targets) {
		
		CHV.fn.list_editor.clearSelection();
		
		if(/image/.test(dealing_with)) {
			
			/*if( (response.request.editing && response.request.editing.new_album == "true") || (response.request.album && response.request.album.new == "true")) {
				//CHV.fn.list_editor.updateUserCounters("album", 1);
			}*/
			
			if(dealing_with == "image") { // single
				CHV.fn.list_editor.updateItem("[data-id="+$targets.data("id")+"]", response.image, "move");
			} else {
				$targets.each(function() {
					CHV.fn.list_editor.updateItem("[data-id="+$(this).data("id")+"]", response, "move", false);
				});
				PF.fn.growl.expirable(PF.fn._s("The content has been moved."));
			}
			
		} else {
			
			// /album?
			if(CHV.obj.resource.type == "album") {
				CHV.fn.list_editor.moveFromList($targets);
			} else {
				PF.fn.growl.expirable(PF.fn._s("The content has been moved."));
			}
			
			if(typeof response.albums_old !== "undefined") {
				for(var i=0; i<response.albums_old.length; i++) {
					CHV.fn.list_editor.updateAlbum(response.albums_old[i]);
				}
			} else {
				CHV.fn.list_editor.updateAlbum(response.old_album);
			}
			
			if(response.album) {
				
				// New album
				if(typeof response.albums_old !== "undefined" ? response.request.album.new == "true" : response.request.editing.new_album == "true") {
					
					// Add option select to modals
					CHV.fn.list_editor.addAlbumtoModals(response.album);
					
					var old_count = parseInt($("[data-text=album-count]").text()) - 1;
					
					$(PF.obj.listing.selectors.pad_content).each(function() {
						
						var list_count = $(this).find(PF.obj.listing.selectors.list_item).length;
						
						if(list_count == 0) {
							return;
						}
						
						var params = PF.fn.deparam($(this).closest(PF.obj.listing.selectors.content_listing).data("params"));
						
						if(params.sort == "date_desc" || old_count == list_count) {
							$(this)[params.sort == "date_desc" ? "prepend" : "append"](response.album.html);
						}
						
					});
				} else {
					CHV.fn.list_editor.updateAlbum(response.album);
				}
			}
						
			PF.fn.listing.columnizerQueue();
			PF.fn.listing.refresh(0);
		}
		
	}
	
};

// Queuezier!
CHV.fn.queuePixel = function() {
	var img = '<img data-content="queue-pixel" src="'+ PF.obj.config.base_url + '?queue&r=' + PF.fn.generate_random_string(32) +'" width="1" height="1" alt="" style="display: none;">';
	$("body").append(img);
}