/*
	*************************
	Shape Share
	*************************

	---
	Developer(s)
	---

	Jason Mayo
	http://bymayo.co.uk
	http://twitter.com/madebymayo

*/

	// ------------------------
	// Build
	// ------------------------

		(function ($) {

			$.fn.shapeShare = function(options) {

				//
				// Settings
				//

			        var settings = $.extend(
				        true,
			        	{
							debug: false,
							window: 'popup',
							hashtags: '',
							shortenUrl: {
								enable: false,
								login: '',
								apiKey: ''
							},
							popupSize: {
					        	width: '600',
					        	height: '500'
							},
							twitter: {
								account: '',
								content: 'title'
							}
						},
						options
					);

				//
				// Debugging
				//

					function debug(name, message, seperate) {

						if(settings.debug) {
							if (seperate) {
								console.log('[Shape Share]' + '[' + name + '] ');
								console.log(message);
							}
							else {
								console.log('[Shape Share]' + '[' + name + '] ' + message);
							}
						}

					}

		        //
		        // Vars
		        //

		        	var dataObjects = ['site_name', 'title', 'description', 'type', 'image', 'url'],
		        		dataLengthObjects = ['title', 'description', 'url', 'account', 'hashtags'],
		        		dataVals = {},
		        		dataLengths = {},
		        		dataValShortUrl;

		        	var contentTwitterMaxLength = 140,
		        		contentTwitterAdjust = 12;

		        //
		        // Get Data
		        //

		        	function getData(object) {

						for (var index = 0; index < dataObjects.length; index++) {

							dataVals[dataObjects[index]] = $('meta[property="og:' + dataObjects[index] + '"]').attr('content');

						}

						debug('Data Values', dataVals, true);

		        	}

		        	getData();

		        //
		        // Shorten URL
		        //

		        	function shortenUrl(){

			        	if (settings.shortenUrl.enable === true) {

							debug('Shorten URL Enabled', 'True');

						    $.getJSON(
						        "http://api.bitly.com/v3/shorten?callback=?",
						        {
						            "format": "json",
						            "apiKey": settings.shortenUrl.apiKey,
						            "login": settings.shortenUrl.login,
						            "longUrl": dataVals.url
						        },
						        function(response) {
							        if (response.status_code == '500') {
		        			        	calculateLengths();
										debug('Shorten URL', 'False');
										debug('Shorten URL Error', response, true);
							        }
							        else {
								        dataVals.url = response.data.url;
		        			        	calculateLengths();
										debug('Shorten URL', 'True');
										debug('Shorten URL (After)', dataVals.url);
							        }
						        }
						    );

			        	}
			        	else {
				        	calculateLengths();
							debug('Shorten URL Enabled', 'False');
			        	}

		        	}

		        	shortenUrl();

		        //
		        // Calculate Lengths
		        //

					function calculateLengths() {

						for (var index = 0; index < dataLengthObjects.length; index++) {

							var lengthType = dataLengthObjects[index];

							if (lengthType === 'account') {
								// Length - Account
								dataLengths[lengthType] = settings.twitter.account.length;
							}
							else if (lengthType === 'hashtags') {
								// Length - Hashtags
								dataLengths[lengthType] = settings.hashtags.length;
							}
							else {
								// Length - Other
								dataLengths[lengthType] = dataVals[lengthType].length;

							}

						}

						debug('Calculate Lengths', dataLengths, true);

					}


				//
				// Content Slice
				//

					function contentSlice(contentType, value){

						var sliceCalc, sliceTotal;

						if (contentType === 'title') {

							sliceCalc = dataLengths.title + value;
							debug('Content Slice Calc', sliceCalc);

							if (sliceCalc <= contentTwitterMaxLength) {
								return dataVals.title;
							}
							else {
								sliceTotal = sliceCalc - contentTwitterMaxLength;
								debug('Content Slice Total', sliceTotal);
								return dataVals.title.slice(0,-sliceTotal) + '...';
							}


						}
						else {

							sliceCalc = dataLengths.description + value;
							debug('Content Slice Calc', sliceCalc);

							if (sliceCalc <= contentTwitterMaxLength) {
								return dataVals.description;
							}
							else {
								sliceTotal = sliceCalc - contentTwitterMaxLength;
								debug('Content Slice Total', sliceTotal);
								return dataVals.description.slice(0,-sliceTotal) + '...';
							}
						}

					}

		        //
		        // Alertnative Content
		        //

		        	// Twitter

					function contentTwitter(){

						var contentCalculator = ((dataLengths.url + dataLengths.hashtags) + (dataLengths.account + contentTwitterAdjust)),
							content = contentSlice(settings.twitter.content, contentCalculator) + ' via @' + settings.twitter.account + ' ' + settings.hashtags,
							contentLength = content.length;

						debug('Content Length (Twitter)', contentCalculator + dataLengths.url);
						debug('Content (Twitter)', content);
						debug('Content Length (Twitter)', contentLength + dataLengths.url);

						return content;

					}

		        //
		        // Create URL
		        //

		        	function createUrl(object) {

			        	// Get Share Type

				        	shareType = $(object).data('share');

				        // Generate URL

							switch (shareType) {

							    case "facebook":
							        shareURL = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(dataVals.url);
							        break;

							    case "twitter":
							        shareURL = 'https://twitter.com/intent/tweet?original_referer=' + encodeURIComponent(dataVals.url) + '&text=' + encodeURIComponent(contentTwitter()) + '&url=' + encodeURIComponent(dataVals.url);
							        break;

							    case "pinterest":
							        shareURL = 'https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(dataVals.url) + '&media=' + encodeURIComponent(dataVals.image) + '&description=' + encodeURIComponent(dataVals.description);
							        break;

								case "googleplus":
							        shareURL = 'https://plus.google.com/share?url=' + encodeURIComponent(dataVals.url);
							        break;

							}

						// Load

							switch(settings.window) {

								case "popup":
									window.open(shareURL, shareType, 'menubar=no,toolbar=0,status=0,width=' + settings.popupSize.width + ',height=' + settings.popupSize.height);
									break;

								case "window":
									window.open(shareURL, '_blank');
									break;

							}

		        	}

		        //
		        // Bind
		        //

		        	$(this).click(
		        		function(e){

							createUrl(this);
							e.preventDefault();

		        		}
		        	);

		    };

		}( jQuery ));