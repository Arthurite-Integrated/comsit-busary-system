var loadingHtml = '<img src="images/loader.gif" width="100" height="100" alt="loading">';//loader.gif; //"I am Loading..."; // this could be an animated image
      var imageLoadingHtml = "Image loading...";
    	var http = getXMLHTTPRequest();

      //------------------------------------------------------------------------------
	  
	  // Cross over function - the function is to exchange variable value between two forms in the same page.

// **********************************************************End of cross over function
	  
function uploadImage() {
				  		
					
			if(document.getElementById('fileno').value=='')
			  { alert('Error, please enter Staff File Number'); exit;}
        var uploadedImageFrame = window.uploadedImage;
    	  uploadedImageFrame.document.body.innerHTML = loadingHtml;
    	  // VALIDATE FILE
		//  		alert("Helllloooooooo");

        var imagePath = uploadedImageFrame.imagePath;
			//	  		alert("Helllloooooooo111111");

        
//		alert('Muyideen');
        //imageForm.submit();
		frmbio.submit();
      }
	 
function uploadImage2() {
				  		
					//	alert("Helllloooooooo");

        var uploadedImageFrame = window.uploadedImage2;
    	  uploadedImageFrame.document.body.innerHTML = loadingHtml;
    	  // VALIDATE FILE
		//  		alert("Helllloooooooo");

        var imagePath = uploadedImageFrame.imagePath;
			//	  		alert("Helllloooooooo111111");

        
//		alert('Muyideen');
        imageForm2.submit();
      }
      //----------------------------------------------------------------

      //----------------------------------------------------------------
      function getXMLHTTPRequest() {
      	try {
        	xmlHttpRequest = new XMLHttpRequest();

      	}
      	catch(error1) {
        	try {
          	xmlHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
          }
          catch(error2) {
      	    try {
      		    xmlHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
      	    }
      	    catch(error3) {
      		    xmlHttpRequest = false;
      	    }
          }
        }
        return xmlHttpRequest;
		
      }
      //----------------------------------------------------------------
	//*********Set Focus on Date of Birth  
	  
	  	 