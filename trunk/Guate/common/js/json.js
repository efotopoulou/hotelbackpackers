if(!this.JSON){JSON=function(){function f(n){return n<10?'0'+n:n}Date.prototype.toJSON=function(a){return this.getUTCFullYear()+'-'+f(this.getUTCMonth()+1)+'-'+f(this.getUTCDate())+'T'+f(this.getUTCHours())+':'+f(this.getUTCMinutes())+':'+f(this.getUTCSeconds())+'Z'};var e=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapeable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(b){escapeable.lastIndex=0;return escapeable.test(b)?'"'+b.replace(escapeable,function(a){var c=meta[a];if(typeof c==='string'){return c}return'\\u'+('0000'+(+(a.charCodeAt(0))).toString(16)).slice(-4)})+'"':'"'+b+'"'}function str(a,b){var i,k,v,length,mind=gap,partial,value=b[a];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(a)}if(typeof rep==='function'){value=rep.call(b,a,value)}switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null'}gap+=indent;partial=[];if(typeof value.length==='number'&&!(value.propertyIsEnumerable('length'))){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null'}v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v}if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value,rep);if(v){partial.push(quote(k)+(gap?': ':':')+v)}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value,rep);if(v){partial.push(quote(k)+(gap?': ':':')+v)}}}}v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v}}return{stringify:function(a,b,c){var i;gap='';indent='';if(c){if(typeof c==='number'){for(i=0;i<c;i+=1){indent+=' '}}else if(typeof c==='string'){indent=c}}rep=b;if(b&&typeof b!=='function'&&(typeof b!=='object'||typeof b.length!=='number')){throw new Error('JSON.stringify');}return str('',{'':a})},parse:function(c,d){var j;function walk(a,b){var k,v,value=a[b];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v}else{delete value[k]}}}}return d.call(a,b,value)}e.lastIndex=0;if(e.test(c)){c=c.replace(e,function(a){return'\\u'+('0000'+(+(a.charCodeAt(0))).toString(16)).slice(-4)})}if(/^[\],:{}\s]*$/.test(c.replace(/\\["\\\/bfnrtu]/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+c+')');return typeof d==='function'?walk({'':j},''):j}throw new SyntaxError('JSON.parse');}}}()} 
