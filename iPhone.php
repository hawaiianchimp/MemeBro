<?php

?>
<html>
	<head>
	<title>
		MEME BRO
	</title>
	<link rel="stylesheet" href="css/global.css" type="text/css" media="screen" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-startup-image" href="images/fb-splash.png">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="user-scalable=no,width=device-width,height=device-height" />
	<link rel="apple-touch-icon-precomposed" href="/images/memebro256.png"/>
	<meta name="apple-touch-fullscreen" content="yes" />
	<script src="http://code.jquery.com/jquery-1.7.2.min.js" type="text/javascript"></script>
	<link href='http://fonts.googleapis.com/css?family=Knewave|Source+Sans+Pro:200,400,700|Dosis:200,400,800|Cherry+Cream+Soda|Flamenco:300,400|Arbutus|Chewy|Bowlby+One|Mrs+Sheppards|Stardos+Stencil:400,700|Quicksand:300,400,700' rel='stylesheet' type='text/css'>
	</head>
		<div id="iPhoneBro" class="iPhone gen">
			<div class="title">
				<input type="submit" class="back button" value="Messages">
					<span class="contact_name button">(614) MEME-BRO</span>
				<input type="submit" class="edit button" value="Edit">
			</div>
			<div id="conversation" class="conversation ">
				<div class="time"><div class="time"><p>Aug 9, 2012 3:43 AM</p></div></div>
				<div class="text receive"><div class="reflect"></div><p>Try "+popular", "+trending", or "+new" for meme lists</p></div>
				<div class="text sent" id="query"><div class="reflect"></div><p><?php switch(rand(1, 3)){ case 1: echo '+popular'; break; case 2: echo '+trending';break; case 3: echo '+new';break; }?></p></div>
				<div class="text receive" id="popular"><div class="reflect"></div><p></p></div>
				<div class="text sent"><div class="reflect"></div><p>Invited to play a game, SAW 7 #badluckbrian</p></div>
				<div class="text receive"><div class="reflect"></div><p>Processing MMS... <a href="http://memebro.com/pf6t0">http://memebro.com/pf6t0</a></p></div>
				<div class="text receive"><div class="reflect"></div><p><a href="http://memebro.com/pf6t0"><img class="addthis_shareable" src="http://cdn.memegenerator.net/instances/400x/24506893.jpg" /></a></p></div>
				<div class="clear"></div>
			</div>
			<div class="message"><input id="imessage" type="text" name="imessage" value="" placeholder="Text Message"><input id="send_btn" type="submit" value="Send" onClick="sendSMS();"><input id="phone" type="hidden" name="number" value="<?php echo $_SERVER['REMOTE_ADDR'].':'.$_SERVER['HTTP_USER_AGENT'] ?>"></div>
		</div>
		
		<a href="sms:+1-614-MEME-BRO"></a>
		</section>
		
		<script type="text/javascript">
		
		
		function replaceURLWithHTMLLinks(text) {
		    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
		    return text.replace(exp,"<a href='$1'>$1</a>"); 
		} 
		
		$(document).ready(function(){
			var button = $('#send_btn');
		     disable(button);
		     $('#imessage').keyup(function(){
		        if($(this).val() != ''){
		           enable(button);
		        }
		     });
		 });
		 
		 function disable(button){
		      button.attr('disabled','disabled');
		      button.attr('class','disabled');
		 }
		 
		 function enable(button)
		 {
			 button.removeAttr('disabled');
		 }
		 
		 function stripslashes(str) {
		  return str.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0');
		 }
		
		(function liveUpdate() {
		var texts =new Array();
		var conversation = $('#live');
		
		$('#popular').find('p').load('memes.php','query=' + $('#query').find('p').html(), null);
		
		conversation.find('.text').hide();
		
		jQuery.getJSON('feed.php?count=20&success=1', function(data) {
		    
		      $.each(data, function(key, val) {
		      	if(val['success'] == 1)
		      	{
			      	var newText = $('<div class="text sent" style="display:none;"><div class="reflect"></div><p></p></div>');
			        newText.hide();
			        newText.find('p').html(replaceURLWithHTMLLinks(stripslashes(stripslashes(stripslashes(val['text'])))));
			        conversation.append(newText);
			        
			        var newResponse = $('<div class="text receive" style="display:none;"><div class="reflect"></div><p></p></div>');
			        newResponse.hide();
			        newResponse.find('p').html(replaceURLWithHTMLLinks(stripslashes(stripslashes(stripslashes(val['response'])))));
			        
			        conversation.append(newResponse);
			        
			        var newImg = $('<div class="text receive" style="display:none;"><div class="reflect"></div><p></p></div>');
			        newImg.hide();
			        newImg.find('p').html('<a href="' + stripslashes(val['url']) + '"><img class="addthis_shareable" src="' + stripslashes(stripslashes(stripslashes(val['img_url']))) + '" /></a>' );
			        
			        conversation.append(newImg);
			    }
		      });
		      
		      $('#live div.sent').first().delay(500).show("normal");
		      
		      $('#live div.sent').find('div').first().delay(500).show("normal", function showOdd() {
		          $(this).next('div').delay(500).show("normal", showOdd);
		          scrollDown(conversation);
		      	}
		      );
		      
		      $('#live div.receive').first().delay(1000).show("normal", function showEven() {
		          $(this).next('div').delay(1800).show("normal", showEven);
		          scrollDown(conversation);
		      	}
		      );
		      
		      $('#live div.receive').first().delay(1000).show("normal", function showPic() {
		          $(this).next('div').delay(1800).show("normal", showPic);
		          scrollDown(conversation);
		      	}
		      );
		  });
		})();
		
		function sendSMS()
		{
			var text = $('#imessage').val();
			if(text != '')
			{
				sendText(text);
				receiveText(text);
			}
		}
		
		function scrollDown(div)
		{
			  div.animate({ scrollTop: 10000 }, "slow");
		}
		
		function sendText(text)
		{
			var conversation = $('#conversation');
			var button = $('#send_btn');
			
			var date = new Date();
			var newTime = $('<div class="time"><p>'+ getDate() + '</p></div>');
			newTime.hide();	
			conversation.append(newTime);
			
			var newText = $('<div class="text sent"><div class="reflect"></div><p>' + text + '</p></div>');
			newText.hide();
			conversation.append(newText);
			
			newText.show('normal');
			newTime.show('fast');
			scrollDown(conversation);
			disable(button);
			$('#imessage').val('');
		}
		
		function receiveText(smsText)
		{
			var button = $('#send_btn');
			var newText = $('<div class="text receive"><div class="reflect"></div><p></p></div>');
			var newImg = $('<div class="text receive"><div class="reflect"></div><p></p></div>');
			newImg.hide();
			newText.hide();
			var conversation = $('#conversation');
			var sender = $('#phone').val();
			
			$.post('handler.php', {
				text : smsText,
				type : 'SMS',
				number : sender
			}, 
			function(data){ 
				newText.find('p').html(replaceURLWithHTMLLinks(data['response']));
				conversation.append(newText);
				newText.show('fast');
				
				if(data['meme_data']['success'])
				{
					console.log(data);
					newImg.find('p').html('<a class="addthis_button_compact" href="' + data['short_url'] + '"><img class="addthis_shareable" src="' + data['meme_data']['result']['instanceImageUrl'] + '" /></a>');
					conversation.append(newImg);
					newImg.delay('2000').show('fast', function(){
						scrollDown(conversation);
						d($('.addthis_shareable'));
						var addthis_config = {     
						    services_overlay:'facebook,twitter,reddit,google+,more'
						};
					});
				}
				scrollDown(conversation);
				enable(button);
				
			}, "json");
			
		
		}
		
		function getDate()
		{
			var a_p = "";
			var d = new Date();
			var curr_hour = d.getHours();
			if (curr_hour < 12)
			   {
			   a_p = "AM";
			   }
			else
			   {
			   a_p = "PM";
			   }
			if (curr_hour == 0)
			   {
			   curr_hour = 12;
			   }
			if (curr_hour > 12)
			   {
			   curr_hour = curr_hour - 12;
			   }
			
			var curr_min = d.getMinutes();
			
			curr_min = curr_min + "";
			
			if (curr_min.length == 1)
			   {
			   curr_min = "0" + curr_min;
			   }
			   
		   var m_names = new Array("Jan", "Feb", "Mar", 
		   "Apr", "May", "Jun", "Jul", "Aug", "Sep", 
		   "Oct", "Nov", "Dec");
		   
		   var curr_date = d.getDate();
		   var curr_month = d.getMonth();
		   var curr_year = d.getFullYear();
			
			return m_names[curr_month] + " " + curr_date + ", " + curr_year + ' '+ curr_hour + ":" + curr_min + " " + a_p;
			
		}
		
		</script>
		<script type="text/javascript">
			var addthis_config = {     
			    services_overlay:'facebook,twitter,reddit,google+,more'
			}
		</script>
		
		<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-502407f64d3ce404"></script>
		
		
		<script type="text/javascript">
		
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-33906955-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>
</html>