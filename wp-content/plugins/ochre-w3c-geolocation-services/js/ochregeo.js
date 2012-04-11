/*
 * Ochre GeoLocation Services for Wordpress
 * Copyright (c) 2010-2011 Ochre Development Labs
 * http://www.ochrelabs.com/
 */

function ochre_geo_push(data)
{
	jQuery.post(OCHREGEO.ajaxurl,{
		'action':'ochregeos',
		'_ochregeo_postid':OCHREGEO.postid,
		'_ochregeo_result':data
		},
		function(ret){
			if(ret=="")
				return;
			var res = jQuery.parseJSON(ret);
			if(res.r){
				switch(res.r){
					case 'execjs':
						if(res.rr){
							eval(res.rr);
						}
					break;
					case 'redir':
						window.location=res.rr;
					break;
					case 'refresh':
						window.reload();
					break;
					default:
						alert("Unknown '"+res.r+"'");
					break;
				}

			}
		}
	);
}

(function() {
    if(navigator.geolocation ) {
      function getLocation(pos)
      {
	ochre_geo_push(pos.coords.latitude+":" + pos.coords.longitude+":0:"+pos.coords.accuracy);
      }

      function unknownLocation(err)
      {
	ochre_geo_push('unknown');
      }

      navigator.geolocation.getCurrentPosition(getLocation, unknownLocation);

    } else {
        ochre_geo_push('nosupport');
    }

})();

