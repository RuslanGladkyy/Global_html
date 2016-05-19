iFrameResize(
	{
		log : false,
		enablePublicMethods : true,
	}
);


/*
//For Debugging

iFrameResize({
	log                     : true,                  // Enable console logging
	enablePublicMethods     : true,                  // Enable methods within iframe hosted page
	enableInPageLinks       : true,
	resizedCallback         : function(messageData){ // Callback fn when resize is received
		jQuery('p#callback').html(
			'<b>Frame ID:</b> '    + messageData.iframe.id +
			' <b>Height:</b> '     + messageData.height +
			' <b>Width:</b> '      + messageData.width +
			' <b>Event type:</b> ' + messageData.type
		);
	},
	messageCallback         : function(messageData){ // Callback fn when message is received
		jQuery('p#callback').html(
			'<b>Frame ID:</b> '    + messageData.iframe.id +
			' <b>Message:</b> '    + messageData.message
		);
		alert(messageData.message);
	},
	closedCallback         : function(id){ // Callback fn when iFrame is closed
		jQuery('p#callback').html(
			'<b>IFrame (</b>'    + id +
			'<b>) removed from page.</b>'
		);
	}
});
*/
