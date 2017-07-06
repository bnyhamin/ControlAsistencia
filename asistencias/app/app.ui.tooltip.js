$(function () {
	$.fn.info = function(settings){
		
		var newHtml='<table class="popup">';		
		  	newHtml='<tbody><tr>';
		  		newHtml='<td id="topleft" class="corner"></td>';
		  		newHtml='<td class="top"></td>';
		  		newHtml='<td id="topright" class="corner"></td>';
		  	newHtml='</tr>';
		  	newHtml='<tr>';
		  		newHtml='<td class="left"></td>';
		  		newHtml='<td><table class="popup-contents">';
		  			newHtml='</tr>';
		  		newHtml='</tbody></table>';

		  		newHtml='</td>';
		  		newHtml='<td class="right"></td>';
		  	newHtml='</tr>';
		  	newHtml='<tr>';
		  		newHtml='<td class="corner" id="bottomleft"></td>';
		  		newHtml='<td class="bottom"><img width="30" height="29" alt="popup tail" src="../../../agencia/views/app/css/themes/theme/images/bubble-tail2.png"/></td>';
		  		newHtml='<td id="bottomright" class="corner"></td>';
		  	newHtml='</tr>';
		  newHtml='</tbody>';
		newHtml='</table>';
		
  	// options
			var distance = 5;
			var time = 250;
			var hideDelay = 250;
			
			var hideDelayTimer = null;
			
			// tracker
			var beingShown = false;
			var shown = false;
			var x=y=0;
			
			return this.each(function(){
				var popup = $('div.popup');
				popup.html(newHtml);
						$(this).hover(
							function(e){
								//$('.popup').fadeIn(100);
								if (hideDelayTimer) 
									clearTimeout(hideDelayTimer);
								
								// don't trigger the animation again if we're being shown, or already visible
								if (beingShown || shown) {
									return;
								}
								else {
//									$("html").click(function(e){ alert("X: " + e.pageX +", Y:"+ e.pageY); });
									//$(this).click(function(e){
										x=e.pageX;
										y=e.pageY;
									//});
									beingShown = true;
									// reset position of popup box
									popup.css({
										top: (y-75),
										left: (x),
										display: 'block' // brings the popup back in to view
									})				// (we're using chaining on the popup) now animate it's opacity and position
									.animate({
										top: '-=' + distance + 'px',
										opacity: 1
									}, time, 'swing', function(){
										// once the animation is complete, set the tracker variables
										beingShown = false;
										shown = true;
									});
								}
							},
							function(){
								//$('.popup').fadeOut(100);	
								// reset the timer if we get fired again - avoids double animations
								if (hideDelayTimer) 
									clearTimeout(hideDelayTimer);

								// store the timer so that it can be cleared in the mouseover if required
								hideDelayTimer = setTimeout(function(){
									hideDelayTimer = null;
									popup.animate({
										top: '-=' + distance + 'px',
										opacity: 0
									}, time, 'swing', function(){
										// once the animate is complete, set the tracker variables
										shown = false;
										// hide the popup entirely after the effect (opacity alone doesn't do the job)
										popup.css('display', 'none');
									});
								}, hideDelay);
							}
						);
					});			
			
			
		};
});

/*
 * 
 *   	
 *   return this.each(function(){
			$(this).hover(
				function(){
					popup.fadeIn(100);
					if (hideDelayTimer) 
						clearTimeout(hideDelayTimer);
					
					// don't trigger the animation again if we're being shown, or already visible
					if (beingShown || shown) {
						return;
					}
					else {
						beingShown = true;
						
						// reset position of popup box
						popup.css({
							top: -100,
							left: -33,
							display: 'block' // brings the popup back in to view
						})				// (we're using chaining on the popup) now animate it's opacity and position
						.animate({
							top: '-=' + distance + 'px',
							opacity: 1
						}, time, 'swing', function(){
							// once the animation is complete, set the tracker variables
							beingShown = false;
							shown = true;
						});
					}
				},
				function(){
					popup.fadeOut(100);	
					// reset the timer if we get fired again - avoids double animations
					if (hideDelayTimer) 
						clearTimeout(hideDelayTimer);
					
					// store the timer so that it can be cleared in the mouseover if required
					hideDelayTimer = setTimeout(function(){
						hideDelayTimer = null;
						popup.animate({
							top: '-=' + distance + 'px',
							opacity: 0
						}, time, 'swing', function(){
							// once the animate is complete, set the tracker variables
							shown = false;
							// hide the popup entirely after the effect (opacity alone doesn't do the job)
							popup.css('display', 'none');
						});
					}, hideDelay);
				}
			);
		});
 * 
 * 
 */