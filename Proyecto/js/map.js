//<![CDATA[ 				
		$(function() {
 			var useragent = navigator.userAgent;
  			var mapdiv = document.getElementById("map_canvas");
 			var mapitem = document.getElementById("map_item");
  			var doc = document.getElementById("doc");

       
  			if ((useragent.indexOf('Android 3.') != -1) && (screen.width >= 800) && (screen.height >= 800)) {
 				doc.style.width = '780px';
 				doc.style.maxWidth = '1240px';
				doc.style.margin = 'auto';  //  '0 0.4em';
  				doc.style.padding = '0 0.8em';
  					  				
 				mapdiv.style.height = '580px';
 					
 				mapitem.style.margin = '0 0 1.5em';
 				mapitem.style.padding = '0.8em';

			} else if ((useragent.indexOf('Android 2.') != -1 ) || (useragent.indexOf('Android 3.') != -1 )) {
 				//alert('android phone');
 				doc.style.width = screen.width;
 				doc.style.maxWidth = '490px';
  				doc.style.margin = '0 0.2em';
  				doc.style.padding = '0 0.3em';
  					
 				mapdiv.style.height = '300px';
 				
 				mapitem.style.margin = '0 0 1em';
 				mapitem.style.padding = '0.4em';
 			} else {	
  				//alert('desktop or not android');
  				doc.style.width = '900px';
  				doc.style.minWidth = '800px';
  				doc.style.maxWidth = '900px';
  				doc.style.margin = 'auto';
  				doc.style.padding = '2em';
  					
    			mapdiv.style.height = '450px';
    				
 				mapitem.style.margin = '0 0 2em';
 				mapitem.style.padding = '1em';

  			}
			var data = [];
			var tags = [];
			tags.push("restaurante");
			tags.push("hotel");
			tags.push("turistico");
			//tags.push("");

			$.ajax({ url:'http://madeinatitlan.com/UVGPruebas/info/usrf101.php?sp=r&format=json',
						 async: false,
						 dataType: 'json',
						 success: function(json){
								data = json.puntos;
							}
					   });
	
			$('#map_canvas').gmap({ 'zoom' : 15,'navigationControlOptions': {'position':google.maps.ControlPosition.TOP_LEFT}, 'center':new google.maps.LatLng(14.566667, -90.733330), 'streetViewControl': false, 'callback': function(map) {
				//$.getJSON( 'http://madeinatitlan.com/UVGPruebas/info/usrf101.php?sp=r&format=json', llegadaDatos);  
				
				
				//llegadaDatos();
				$.each( data, function(i,m) { 
					
							$('#map_canvas').gmap('addMarker', {'tag':m.punto.tipoPrincipal,'icon':new google.maps.MarkerImage(darIcono(m.punto.tipoPrincipal)), 'position': new google.maps.LatLng(m.punto.latitud, m.punto.longitud ), 
							'title':m.punto.nombre,'draggable':false, 'bound': false, }, function(map,marker) 
							{
							var append = '<form id="dialog'+marker.__gm_id+'" onsubmit="return false;" method="get" action="/" style="display:none;">' +
							'<p><label for="nombre"></label>"'+m.punto.nombre+'"</p><p><label for="direccion">Direcci�n: </label>"'+m.punto.direccion+'"</p>';
							if (m.punto.telefono != null) {append = append + '<p><label for="telefono">Tel: </label>"'+m.punto.telefono+'"</p>';}
							if (m.punto.website != null){append = append +'<p><label for="website">Website: </label>"'+m.punto.website+'"</p></form>';}
							if (m.punto.tipoPrincipal!= null){append = append +'<p><label for="tipo">Tipo: </label>"'+m.punto.tipoPrincipal+'"</p></form>';}
							
								$('#dialog').append(append);
								//encontrarLocacion(marker.getPosition(),marker);
							}).click(function(){
								map.panTo($(this).get(0).getPosition());
							    abrirDialogo(this);
							}); //addmarker
				}); //.each
				
			
			 }}); // Gmap options 
		 
			var markerCluster = new MarkerClusterer($('#map_canvas').gmap('getMap'), $('#map_canvas').gmap('getMarkers'));				
			
			function darIcono(tipoPunto)
			{
				var icono = '../marcadores/';
				if (tipoPunto == "restaurante"){return icono + '1.png';}
				else if (tipoPunto == "hotel"){return icono + '2.png';}
				else if (tipoPunto == "turistico"){return icono+'3.png';}
				return icono+'4.png';
			}
			
			
		
			function abrirDialogo(el) {
					$('#dialog'+el.__gm_id).dialog({'modal':true, 'title': 'Informaci�n', 'buttons': { 
						"Comentar": function() {
							//$(this).dialog( "close" );
							//var window = wxHtmlWindow.create(this);
							//window.loadPage(OAuth.html);
							//window.open('OAuth.html','Comentar','menubar=no');
							//openindex();	
							
						},
						"Cerrar": function() {
							$(this).dialog( "close" );
						}
					}});
				}
				
			function openindex()
			{ 
				OpenWindow=window.open("", "newwin", "height=250, width=250,toolbar=no,scrollbars="+scroll+",menubar=no");
			
				OpenWindow.document.close()
				self.name="main"
			}	
		
	
		
	
		//function loadScript() {
		//	detectBrowser();
		//	loadMaps();
		//} 
            });
        //]]>