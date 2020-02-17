function myModal(idModal="myModal", idImg="myImg", idModalImg="img01", idCaption="caption", classClose="closeButton")
{
// Get the modal
var modal = document.getElementById(idModal);

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById(idImg);
var modalImg = document.getElementById(idModalImg);
var captionText = document.getElementById(idCaption);
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
//var span = document.getElementsByClassName("close")[0];
var span = document.getElementById(classClose);


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
}
