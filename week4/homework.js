function Replace(id, obj) {
	
	str = document.getElementById(id).innerHTML;
	var re = new RegExp(Object.keys(obj).join("|"),"gi"); // global, i= ignor case-sensitive /[a-z]/i = /[a-zA-z]/
	
	return document.getElementById(id).innerHTML = str.replace(re,
     	function(matched) { return obj[matched];});  

}