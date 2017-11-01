
var controlsContainer = document.getElementById('color-container'),
    allImages = document.querySelectorAll('.product-image'),
    imagesContainer = document.getElementById('images-container');


controlsContainer.onclick = function(e){
  var target = e.target,
      dataTarget = target.getAttribute('data-image'),      
      activeImage = document.getElementById(dataTarget);
  
  if(dataTarget){
    for( var i = 0; i < allImages.length; i++){
    allImages[i].removeAttribute('data-active');
  }
 
  activeImage.setAttribute('data-active', 'active');
  }  
}
