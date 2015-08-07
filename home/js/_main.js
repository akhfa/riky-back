/*! viewportSize | Author: Tyson Matanich, 2013 | License: MIT */
(function(n){n.viewportSize={},n.viewportSize.getHeight=function(){return t("Height")},n.viewportSize.getWidth=function(){return t("Width")};var t=function(t){var f,o=t.toLowerCase(),e=n.document,i=e.documentElement,r,u;return n["inner"+t]===undefined?f=i["client"+t]:n["inner"+t]!=i["client"+t]?(r=e.createElement("body"),r.id="vpw-test-b",r.style.cssText="overflow:scroll",u=e.createElement("div"),u.id="vpw-test-d",u.style.cssText="position:absolute;top:-1000px",u.innerHTML="<style>@media("+o+":"+i["client"+t]+"px){body#vpw-test-b div#vpw-test-d{"+o+":7px!important}}<\/style>",r.appendChild(u),i.insertBefore(r,e.head),f=u["offset"+t]==7?i["client"+t]:n["inner"+t],i.removeChild(r)):f=n["inner"+t],f}})(this);

/**
 * How to create a parallax scrolling website
 * Author: Petr Tichy
 * URL: www.ihatetomatoes.net
 * Article URL: http://ihatetomatoes.net/how-to-create-a-parallax-scrolling-website/
 */

func_appear = null;
func_disappear = null;
func_menu_click = null;
func_chart = null;

var data1 = "<h2><a class='project' href='https://github.com/Rikysamuel/ParkRanger.git' target='_blank'>Park Ranger</a></h2><p>Park Ranger allow the user to report problems in a certain park. For example if the park was damaged or if there was thugs in there. The report itself will be forwarded to the rightful person, such as POLRI, Dinas Pertamanan dan Pemakaman, etc.</p>";
var data2 = "<h2><a class='project' href='https://github.com/Rikysamuel/VigenereCipher.git' target='_blank'>Vigenere Cipher</a></h2><p>This java applet may allow a user to encrypt/decrypt a plain/cipher text using a key. It also has 4 different methods for encryption/decryption</p>";
var data3 = "<h2><a class='project' href='hhttps://github.com/Rikysamuel/Tubes_AI_2.git' target='_blank'>News Classifer</a></h2><p>This web app may help user to classify a news into certain categories.</p>";
var data4 = "<h2><a class='project' href='javascript:;	' target='_blank'>Tweet Analytics</a></h2><p>It finds all tweets which has been tweeted on the twitter that matched with a certain string inputted by the user. It uses KMP and Boyer-Moore string matching algorithm.</p>";
var data5 = "<h2><a class='project' href='https://github.com/Rikysamuel/IF3111-Tugas-1-Android.git' target='_blank'>Jerry Finder</a></h2><p>This android app find where \"Jerry\" is. The location of Jerry is defined by the server, and the app will show the map. The app also has QR Code Scanner and Compass";
var data6 = "<h2><a class='project' href='javascript:;' target='_blank'>Life to Death</a></h2><p>Life to Death is a game which the player has a goal to find the treasure inside a maze. But inside the maze, there is a lot of zombie that can attack the player.</p>";

var profpic = [];
profpic[0] ="img/photo1.jpg";
profpic[1] ="img/photo2.JPG";
profpic[2] ="img/photo3.jpeg";
profpic[3] ="img/photo4.jpg";
profpic[4] ="img/photo5.jpg";

( function( $ ) {
	
	// Setup variables
	$window = $(window);
	$slide = $('.homeSlide');
	$slideTall = $('.homeSlideTall');
	$slideTall2 = $('.homeSlideTall2');
	$body = $('body');
	
    //FadeIn all sections   
	$body.imagesLoaded( function() {
		setTimeout(function() {
		      
		      // Resize sections
		      adjustWindow();
		      
		      // Fade in sections
			  $body.removeClass('loading').addClass('loaded');
			  
		}, 800);
	});
	
	function adjustWindow(){
		// Init Skrollr
		var s = skrollr.init({
			forceHeight: false,
		    render: function(data) {
		    	var disappear = $('#fb').hasClass('not');
		        console.log(data.curTop);
		        func_menu(data.curTop);
		        func_chart();

		        if (data.curTop >= 500) {
		        	if (data.curTop < 650) {
		        		if (disappear) {
			        		func_appear();
			        	}
		        	}
		        }

		        if (data.curTop >= 650 || data.curTop < 400) {
		        	if (!disappear) {
		        		func_disappear();
		        	}
		        }
		    }
		});
		
		// Get window size
	    winH = $window.height();
	    
	    // Keep minimum height 550
	    if(winH <= 550) {
			winH = 550;
		} 
	    
	    // Resize our slides
	    $slide.height(winH);
	    $slideTall.height(winH*2);
	    $slideTall2.height(winH*3);
	    
	    // Refresh Skrollr after resizing our sections
	    s.refresh($('.homeSlide'));
	    
	}

	func_appear = function setAppear() {
	    $('#fb').animate({"left":"0px"}, "slow").removeClass('not');
	    $('#twitter').animate({"left":"0px"}, "slow").removeClass('not');
	    $('#gmail').animate({"left":"0px"}, "slow").removeClass('not');
	    $('#github').animate({"left":"0px"}, "slow").removeClass('not');
	    $('#linkedin').animate({"left":"0px"}, "slow").removeClass('not');
    }

    func_disappear = function setDisappear() {
    	$('#fb').animate({"left":"-1000px"}, "slow").addClass('not');
    	$('#twitter').animate({"left":"1000px"}, "slow").addClass('not');
    	$('#gmail').animate({"left":"-1000px"}, "slow").addClass('not');
    	$('#github').animate({"left":"1000px"}, "slow").addClass('not');
    	$('#linkedin').animate({"left":"-1000px"}, "slow").addClass('not');
    }

    func_menu = function setMenu(scrollPos) {
    	if(scrollPos >= 0 && scrollPos < 900) {
    		$('#who').addClass('active');
    		$('#edu').removeClass('active');
    		$('#skill').removeClass('active');
    		$('#hobby').removeClass('active');
    	}
    	if (scrollPos >= 900 && scrollPos < 2222) {
    		$('#who').removeClass('active');
    		$('#edu').addClass('active');
    		$('#skill').removeClass('active');
    		$('#hobby').removeClass('active');
    	}
    	if (scrollPos >= 2222 && scrollPos < 3050) {
    		$('#who').removeClass('active');
    		$('#edu').removeClass('active');
    		$('#skill').addClass('active');
    		$('#hobby').removeClass('active');
    	}
    	if (scrollPos >=3050) {
    		$('#who').removeClass('active');
    		$('#edu').removeClass('active');
    		$('#skill').removeClass('active');
    		$('#hobby').addClass('active');
    	}
    }

    func_chart = function chart() {
    	// bar chart data

		var barData = {
		labels : ["VB","LISP","CLIPS","PROLOG","C","C++","C#","JAVA","HTML","CSS","PHP/Laravel","JSF/JSP","Javascript/jQuery","Android","Unity"],
		datasets : [
				{
					fillColor : "#48A497",
					strokeColor : "#48A4D1",
					data : [580,412,345,356,654,765,603,777,782,610,788,658,780,623,597]
				}
			]
		}

		var options = {
			scaleShowLabels : false,
			scaleShowHorizontalLines: true,
		}
		
		// get bar chart canvas
		var income = document.getElementById("income").getContext("2d");
		
		// draw bar chart
		var barChart = new Chart(income).Bar(barData, options);
    }
		
} )( jQuery );

$(document).ready(function (){
    $("#who").click(function (){
        $('html, body').animate({
            scrollTop: $("#top_slide-1").offset().top
        }, 1000);
    });

	$("#edu").click(function (){
        $('html, body').animate({
            scrollTop: $("#top_slide-3").offset().top - 100
        }, 1000);
    });

    $("#skill").click(function (){
        $('html, body').animate({
            scrollTop: $("#top_slide-5").offset().top - 50
        }, 1000);
    });

    $("#hobby").click(function (){
        $('html, body').animate({
            scrollTop: $("#top_slide-7").offset().top
        }, 1000);
    });
});

$(document).ready(function (){
	var i = 0;
	setInterval(fadeDivs, 5000);

	function fadeDivs() {
	    i = Math.floor(Math.random() * profpic.length) - 1;
	    $('#profpic img').fadeOut(100, function(){
	        $(this).attr('src', profpic[i]).fadeIn();
	    })
	    i++;
	}
});

$(document).ready(function (){
	$("body").keydown(function(e) {
		if(e.keyCode == 37) { // left
		    var last = $("#pro_pict img").attr('src');
	        last = last.replace("img/","");
	        last = last.replace(".png","");
	        if (last==1) {
	        	return;
	        } else {
	        	last--;
	        	$("#pro_pict img").fadeOut(500, function() {
				   $(this).attr('src',"img/"+last+".png").fadeIn();
				});
		        $("#pro_content").fadeOut(500, function() {
				   $(this).empty().append(window["data"+last]).fadeIn();
				});
	        }
	  	}
		else if(e.keyCode == 39) { // right
		    var last = $("#pro_pict img").attr('src');
	        last = last.replace("img/","");
	        last = last.replace(".png","");
	        if (last==6) {
	        	return;
	        } else{
	        	last++;
		        $("#pro_pict img").fadeOut(500, function() {
				   $(this).attr('src',"img/"+last+".png").fadeIn();
				});
		        $("#pro_content").fadeOut(500, function() {
				   $(this).empty().append(window["data"+last]).fadeIn();
				});
	        }
		}
	});
});

jQuery(document).ready(function() {
    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
    });
    
    jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })

    jQuery('.logo').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});	

jQuery.fn.rotate = function(degrees) {
    $(this).css({'-webkit-transform' : 'rotate('+ degrees +'deg)',
                 '-moz-transform' : 'rotate('+ degrees +'deg)',
                 '-ms-transform' : 'rotate('+ degrees +'deg)',
                 'transform' : 'rotate('+ degrees +'deg)'});
};

$('.sport').hover(
	function() {
    	$(this).rotate(20);
    	$('#sport_hobby').removeClass('hidden');
	},
	function() {
		$(this).rotate(0);
		$('#sport_hobby').addClass('hidden');
	}
);

$('.music').hover(
	function() {
    	$(this).rotate(20);
    	$('#music_hobby').removeClass('hidden');
	},
	function() {
		$(this).rotate(0);
		$('#music_hobby').addClass('hidden');
	}
);

$('.computer').hover(
	function() {
    	$(this).rotate(20);
    	$('#comp_hobby').removeClass('hidden');
	},
	function() {
		$(this).rotate(0);
		$('#comp_hobby').addClass('hidden');
	}
);