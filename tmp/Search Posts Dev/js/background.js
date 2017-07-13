chrome.runtime.onMessage.addListener(
  function(request, sender, sendResponse) {
	if(request.status && /natashaclub.com/.test(sender.url)){
		chrome.pageAction.show(sender.tab.id);	
	}
  });