/* global wgNamespaceIds, wgFormattedNamespaces, mw, wgServer, wgScript */
$(function(){
	'use strict';

	require(['wikia.window', 'jquery', 'wikia.nirvana', 'JSMessages'], function(window, $, nirvana, msg){

		var d = document,
			category = mw.config.get('videoTemplate'),
			tag = mw.config.get('languageTemplate'),
			requiredError = msg('wikiagameguides-sponsored-required-entry'),
			emptyTagError = msg('wikiagameguides-sponsored-empty-tag'),
			orphanedVideo = msg('wikiagameguides-sponsored-orphaned-video'),
			addCategory = d.getElementById('addCategory'),
			addTag = d.getElementById('addTag'),
			$save = $(d.getElementById('save')),
			form = d.getElementById('contentManagmentForm'),
			$form = $(form),
			ul = form.getElementsByTagName('ul')[0],
			$ul = $(ul),
			setup = function(elem){
				(elem || $ul.find('.video-url')).autocomplete({
					serviceUrl: window.wgServer + window.wgScript,
					params: {
						action: 'ajax',
						rs: 'getLinkSuggest',
						format: 'json',
						ns: window.wgNamespaceIds.video
					},
					appendTo: form,
					onSelect: function(){
						$ul.find('input:focus').next().focus();
					},
					deferRequestBy: 100,
					minLength: 3,
					skipBadQueries: true // BugId:4625 - always send the request even if previous one returned no suggestions
				});
			},
			addNew = function(row, elem){
				$ul.append(row);
				setup($ul.find('.video-url:last'));
				$ul.find('.wiki-input:last').focus();

				$ul.sortable('refresh');
			},
			checkInputs = function(elements){

				elements.each(function(){
					var $this = $(this),
						val = $this.val();


					if(val === '') {
						$this
							.addClass('error')
							.popover('destroy')
							.popover({
								content: requiredError
							});
					} else {
						$this
							.removeClass('error')
							.popover('destroy');
					}
				});
			},
			checkForm = function(){

				$save.removeClass();

				checkInputs($ul.find('input'));

				var lis = $ul.children(),
					first = lis.first();

				lis.removeClass('error');

				if(!first.hasClass('language')){

					var firsts = first.nextUntil('.language');

					if(first.hasClass('video') || firsts.length) {
						first
							.add(firsts)
							.addClass('error')
							.popover('destroy')
							.popover({
								content: orphanedVideo
							});
					}
				}

				$ul.find('.language').each(function(){
					var $t = $(this),
						$categories = $t.nextUntil('.language');

					if($categories.length === 0) {
						$t.find('.language-input')
							.addClass('error')
							.popover('destroy')
							.popover({
								content: emptyTagError
							});
					}
				});

				if(d.getElementsByClassName('error').length > 0){
					$save.attr('disabled', true);
					return false;
				}else{
					$save.attr('disabled', false);
					return true;
				}
			};

		$form
			.on('keypress', '.video-url', function(ev){
				if(ev.keyCode === 13) addNew(category, $(this).parent());
			})
			.on('keypress', '.wiki-input, .video-title', function(ev){
				if(ev.keyCode === 13) $(this).next().focus();
			})
			.on('focus', 'input', function(){
				checkForm();
			})
			.on('blur', 'input', function(){
				checkForm();
			})
			.on('click', '.remove', function(){
				ul.removeChild(this.parentElement);
				checkForm();
			});

		$(addCategory).on('click', function(){
			addNew(category);
		});

		$(addTag).on('click', function(){
			addNew(tag);
		});

		function getData(li) {
			li = $(li);

			return {
				wiki: li.find('.wiki-input').val(),
				video_title: li.find('.video-title').val(),
				video_name: li.find('.video-url').val()
			}
		}

		$save.on('click', function(){
			var data = [];

			if(checkForm()) {
				$ul.find('.language').each(function(){
					var $t = $(this),
						code = $t.find('.language-input').val(),
						videos = [];

					$t.nextUntil('.language').each(function(){
						videos.push(getData(this));
					});

					data.push({
						code: code,
						videos: videos
					});
				});

				$save.removeClass();
				$form.startThrobbing();

				nirvana.sendRequest({
					controller: 'GameGuidesSpecialSponsored',
					method: 'save',
					data: {
						languages: data
					}
				}).done(
					function(data){
						if(data.error) {
							var err = data.error,
								i = err.length,
								videos = $form.find('.video-url');

							while(i--){
								//I cannot use value CSS selector as I want to use current value
								videos.each(function(){
									if(this.value === err[i]){
										$(this)
											.addClass('error')
											.popover('destroy')
											.popover({
												content: 'This video does not exist'
											});

										return false;
									}
									return true;
								});
							}

							$save.addClass('err');
							$save.attr('disabled', true);
						}else if(data.status){
							$save.addClass('ok');
						}
				}).fail(
					function(){
						$save.addClass('err');
					}
				).always(function(){
					$form.stopThrobbing();
				});
			}
		});

		//be sure modules are ready to be used
		mw.loader.using(['jquery.autocomplete', 'jquery.ui.sortable'], function(){
			$ul.sortable({
				opacity: 0.5,
				axis: 'y',
				containment: '#contentManagmentForm',
				cursor: 'move',
				handle: '.drag',
				placeholder: 'drop',
				update: function(){
					checkForm();
				}
			});

			setup();
		});
	});
});