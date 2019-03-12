var Canvasgif = function(opt){
	
	
}

Canvasgif.prototype = {
	constructor : Canvasgif,
	
	
	
	load_url: function(src, callback){

		var xhr = new XMLHttpRequest();
		// new browsers (XMLHttpRequest2-compliant)
		xhr.open('GET', src, true);

		if ('overrideMimeType' in xhr) {
			xhr.overrideMimeType('text/plain; charset=x-user-defined');
		} else if ('responseType' in xhr) {		// old browsers (XMLHttpRequest-compliant)
			xhr.responseType = 'arraybuffer';
		} else {								// IE9 (Microsoft.XMLHTTP-compliant)
			xhr.setRequestHeader('Accept-Charset', 'x-user-defined');
		}
		
		xhr.onload = function(e) {
			if (this.status != 200) {
				console.log('xhr - response');
			}
			
			// emulating response field for IE9
			if (!('response' in this)) {		
				this.response = new VBArray(this.responseText).toArray().map(String.fromCharCode).join('');
			}
			var data = this.response;
			if (data.toString().indexOf("ArrayBuffer") > 0) {
				data = new Uint8Array(data);
			}

			return data;
			//stream = new Stream(data);
			//setTimeout(doParse, 0);
		};
		xhr.onprogress = function (e) {
			if (e.lengthComputable) doShowProgress(e.loaded, e.total, true);
		};
		xhr.onerror = function() { doLoadError('xhr'); };
		xhr.send();
	},
}