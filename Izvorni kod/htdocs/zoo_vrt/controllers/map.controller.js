app.controller("MapController", function($scope, $state){

	//Variables
	var canvas = document.getElementById("karta");
	var context = canvas.getContext("2d");


	/*
	potrebno zato sto CSS sere
	kad se u map.html mijenja width i height potrebno je i tu promjenit
	*/
	canvas.setAttribute('height', $('#map').height());
	canvas.setAttribute('width', $('#map').width());

	var img = new Image;
	img.src = "/img/karta.png";

	/* TEST */
	var dot={
		x: 300,
		y: 200
	}

	dot=null

	//Functions
	function draw(scale, translatePos){
		context.clearRect(0, 0, canvas.width*scale, canvas.height*scale);
		context.drawImage(img,translatePos.x,translatePos.y, img.width*scale, img.height*scale);

		//crtanje oznake
		if(dot){
			var radius = 10*scale;
			context.strokeStyle = "#df4b26";
			context.lineJoin = "round";
			context.lineWidth = 5*scale;

			context.beginPath();
			context.arc(translatePos.x+dot.x*scale, translatePos.y+dot.y*scale, radius, 0, 2 * Math.PI, false);
			context.fillStyle = 'red';
			context.fill();
			context.stroke();
			//

			context.font = "14px Verdana";
			context.fillStyle = "red";
			context.fillText("Lokacija nastambe",10,20);
		}
		else{
			context.font = "14px Verdana";
			context.fillStyle = "black";
			context.fillText("Karta zoološkog vrta",10,20);
		}
    }

	//Init
	img.onload = function(){
			context.drawImage(img,
				0,0, img.width, img.height,
				0,0, canvas.width, canvas.height);

			//centriraj mapu na tocku
			var translatePos={}
			if(dot){
				translatePos.x= canvas.width/2-dot.x
				translatePos.y=canvas.height/2-dot.y
			}
			else {
				translatePos.x=canvas.width/2-img.width/2
				translatePos.y=canvas.height/2-img.height/2
			}

            var scale = 1;
            var startDragOffset = {};
            var mouseDown = false;

            // add event listeners to handle screen drag
            canvas.addEventListener("mousedown", function(evt){
                mouseDown = true;
                startDragOffset.x = evt.clientX - translatePos.x;
                startDragOffset.y = evt.clientY - translatePos.y;
            });

            canvas.addEventListener("mouseup", function(evt){
                mouseDown = false;
            });

            canvas.addEventListener("mouseover", function(evt){
                mouseDown = false;
            });

            canvas.addEventListener("mouseout", function(evt){
                mouseDown = false;
            });

			var mousewheelevt=(/Firefox/i.test(navigator.userAgent))? "DOMMouseScroll" : "mousewheel" //FF doesn't recognize mousewheel as of FF3.x


			wheelHandler = function(e){

				var max_scale=Math.pow(1.1, 5);
				var min_scale=Math.pow(1.1, 1.0/5);

				var evt=window.event || e //equalize event object
				var delta=evt.detail? evt.detail*(-120) : evt.wheelDelta //delta returns +120 when wheel is scrolled up, -120 when scrolled down
				direction=(delta<=-120)? 1 : -1 //move image index forward or back, depending on whether wheel is scrolled down or up

				if(direction>0 && scale>min_scale) scale/=1.1;
				else if(direction<0 && scale<max_scale) scale*=1.1;

				draw(scale, translatePos);
				evt.detail? evt.detail=0 : evt.wheelDelta=0

				if (evt.preventDefault) //disable default wheel action of scrolling page
					evt.preventDefault()
				else
					return false
			}

			if (canvas.attachEvent) //if IE (and Opera depending on user setting)
				canvas.attachEvent("on"+mousewheelevt, wheelHandler)
			else if (canvas.addEventListener) //WC3 browsers
				canvas.addEventListener(mousewheelevt, wheelHandler, false)


            canvas.addEventListener("mousemove", function(evt){
                if (mouseDown) {
                    translatePos.x = evt.clientX - startDragOffset.x;
                    translatePos.y = evt.clientY - startDragOffset.y;

                    draw(scale, translatePos);
                }
            });

            draw(scale, translatePos);
		}

})