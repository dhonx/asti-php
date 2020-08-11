(function () {
  "use strict";
  var baseLocation = [-10.1749491, 123.5796987],
    mapEl = document.body.querySelector("#map"),
    fieldLatitude = document.body.querySelector("#latitude"),
    fieldLongitude = document.body.querySelector("#longitude");

  var mymap = L.map("map").setView(baseLocation, 13);
  var layer = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '<a href="https://www.openstreetmap.org/copyright">@OpenStreetMap</a>',
  });
  layer.addTo(mymap);

  var marker;
  mymap.on("click", function (e) {
    if (marker) mymap.removeLayer(marker);
    marker = L.marker(e.latlng);
    fieldLatitude.value = e.latlng.lat;
    fieldLongitude.value = e.latlng.lng;
    marker.addTo(mymap);
  });

  if (mapEl.dataset.latitude && mapEl.dataset.longitude) {
    marker = L.marker([mapEl.dataset.latitude, mapEl.dataset.longitude]);
    marker.addTo(mymap);
  }

  if (mapEl.dataset.readonly == "true") {
    mymap.dragging.disable();
    mymap.touchZoom.disable();
    mymap.doubleClickZoom.disable();
    mymap.scrollWheelZoom.disable();
    mymap.boxZoom.disable();
    mymap.keyboard.disable();
    if (mymap.tap) mymap.tap.disable();
    mapEl.style.cursor = "default";
  }
})();
