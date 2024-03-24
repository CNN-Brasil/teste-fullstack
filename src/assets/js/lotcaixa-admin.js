function lotcaixaCopyThisValue(element) {    
	element.select();    
	document.execCommand('copy');  
	element.blur();
	var responseCopyDiv = element.nextElementSibling;
	responseCopyDiv.innerHTML = 'Copied code!';					
	setTimeout(function() {	responseCopyDiv.innerHTML = '';	}, 3000);
}
