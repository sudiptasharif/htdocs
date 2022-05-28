/**
 * Name: Sudipta Sharif
 */
var yelpJSONResponse;
var map;
var businessMarkers;

function initialize() {
   yelpJSONResponse = null;
   businessMarkers = [];
   initMap();
   reset();
}

function initMap() {
   var latitude = 32.75, longitude = -97.13, zoomLevel = 16;
   map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: latitude, lng: longitude },
      zoom: zoomLevel,
   });
}

function sendRequest() {
   // before sending the request, first reset output div 
   reset();

   var url = "proxy.php?";
   var termParam = "term=";
   var limitParam = "limit=10";
   var latitudeParam = "latitude=" + getMapsBoundingLatitude();
   var longitudeParam = "longitude=" + getMapsBoundingLongitude();
   // As per Professor's instruction in class, we don't need to calculate this from 
   // Map's getBounds, as such just setting it to 15,000 meters ~ 9.3 miles
   var radiusParam = "radius=15000"; 

   var xhr = new XMLHttpRequest();
   var searchTerm = document.getElementById('search').value.trim();

   searchTerm = encodeURI(searchTerm);
   termParam += searchTerm;

   url += termParam + "&" + latitudeParam + "&" + longitudeParam + "&"+ radiusParam + "&" + limitParam;
   //l(url);

   xhr.open("GET", url);
   xhr.setRequestHeader("Accept", "application/json");
   xhr.onreadystatechange = function () {
      if (this.readyState == 4) {
         yelpJSONResponse = JSON.parse(this.responseText);
         processResponse();
      }
   };
   xhr.send(null);
} 

function processResponse(){
   var total_businesses = yelpJSONResponse.businesses.length;
   if(total_businesses > 0){
      createWebMashupList(yelpJSONResponse.businesses);
      setInnerHTML("info", "");
      setVisibility("info", false);      
   }else{
      setVisibility("info", true);
      setInnerHTML("info", "Nothing found :( Please try with another search term!");
   }
}

function createWebMashupList(businesses){
   var outputDiv = document.getElementById("output");
   for(var i = 0; i < businesses.length; i++){
      var hElement = document.createElement("h3");
      
      var figureElement = document.createElement("figure");
      var imgElement = document.createElement("img");
      var figurecaptionElement = document.createElement("figcaption");
      
      var pAnchorElement = document.createElement("p");
      var anchorElement =document.createElement("a");
      
      var pRatingElement = document.createElement("p");

      var pAddressElement = document.createElement("p");
      var pPhoneElement = document.createElement("p");

      var businessesLat, businessesLongt;
      var name, image_url, rating, url, address, phone;

      businessesLat = businesses[i].coordinates.latitude;
      businessesLongt = businesses[i].coordinates.longitude;
      
      name = businesses[i].name;
      image_url = businesses[i].image_url;
      rating = businesses[i].rating;
      url = businesses[i].url;
      address = businesses[i].location.display_address.join(", ");
      phone = businesses[i].display_phone;

      hElement.innerHTML = "Business #: " + (i+1);
      
      imgElement.setAttribute("src", image_url);
      imgElement.setAttribute("alt", "bussiness image");
      imgElement.setAttribute("style", "border:2px solid black; border-radius:5px;");
      figurecaptionElement.innerHTML = name;

      anchorElement.innerHTML = name;
      anchorElement.setAttribute("href", url);
      anchorElement.setAttribute("target", "_blank");
      
      pRatingElement.innerHTML = "Rating: " + rating;
      pAddressElement.innerHTML = "Address: " + address;
      pPhoneElement.innerHTML = "Phone: " + phone;

      pAnchorElement.innerHTML = "Name: ";
      pAnchorElement.appendChild(anchorElement);

      figureElement.appendChild(imgElement);
      figureElement.appendChild(figurecaptionElement);

      outputDiv.appendChild(hElement);
      outputDiv.appendChild(pAnchorElement);
      outputDiv.appendChild(pAddressElement);
      outputDiv.appendChild(pPhoneElement);
      outputDiv.appendChild(pRatingElement);
      outputDiv.appendChild(figureElement);

      addMarker(businessesLat, businessesLongt, (i+1)+"");
   }
}

function addMarker(lat, lngt, label){
   var businessLatLng = {
      lat: lat,
      lng: lngt
   };
   var businessMarker = new google.maps.Marker({
      position: businessLatLng,
      map: map,
      label: label,
   });
   businessMarkers.push(businessMarker);
}
function reset(){
   setVisibility("info", false);
   setInnerHTML("info", false);
   resetOutputDiv();
   resetBusinessMarkers();
}

function resetBusinessMarkers(){
   deleteBusinessMarkers();
   businessMarkers = [];
}

function deleteBusinessMarkers(){
   for(var i = 0; i < businessMarkers.length; i++){
      businessMarkers[i].setMap(null);
   }
}

function resetOutputDiv(){
   var outputDiv = document.getElementById("output");  
   var childEle = outputDiv.firstElementChild;
   while(childEle){
      outputDiv.removeChild(childEle);
      childEle = outputDiv.firstElementChild;
   } 
}

function setInnerHTML(elementID,value){
   var ele = document.getElementById(elementID);
   if(ele){
      ele.innerHTML = value;
   }
}

function setVisibility(elementID, visible){
   var ele = document.getElementById(elementID);
   if (visible) {
      ele.style.visibility = "visible";
   } else {
      ele.style.visibility = "hidden";
   }   
}

function getMapsBoundingLatitude(){
   return ((map.getBounds().Ya.i + map.getBounds().Ya.j)/2.0).toFixed(2);
}

function getMapsBoundingLongitude(){
   return ((map.getBounds().Sa.i + map.getBounds().Sa.j)/2.0).toFixed(2);
}

function l(msg){
   console.log(msg);
}

