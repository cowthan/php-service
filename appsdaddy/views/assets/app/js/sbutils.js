
/**
 * 在body的最后加个log，会自动用p标签包起来，并append
 * ——会加到id为log-sb的div中，如果没有，会创建
 * @param msg
 */
function logger(msg){
	var container = $("#log-sb");
	if(container.size() === 0){
		container = $('<div id="log-sb"></div>');
		container.appendTo($("body"));
	}
	var log = $("<p>" + msg + "</p>");
	log.appendTo(container);
}