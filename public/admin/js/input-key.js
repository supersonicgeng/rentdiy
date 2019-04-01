function onlyNum(e) 
{ 	
	//keyCode:IE支持，which:FF支持。
    e=arguments.callee.caller.arguments[0] || window.event; 
	var code = e.keyCode || e.which;
	//alert(code);//srcElement:IE支持，target:FF支持
    //var val = e.srcElement ? e.srcElement : e.target;
	if(!(code==46)&&!(code==8)&&!(code==37)&&!(code==39) && !(code >= 113 && code <=123)) 
	if(!((code>=48&&code<=57)||(code>=96&&code<=105))) 
	e.preventDefault(false); 
} 