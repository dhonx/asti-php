var selectKomponen = document.body.querySelector("#komponen"),
  selectBarang = document.body.querySelector("#barang");

getBarang();

selectKomponen.addEventListener("input", getBarang);

function getBarang() {
  var idKomponen = selectKomponen.value;
  removeAllSelectBarangOptions();
  axios.get("get_barang?id=" + idKomponen).then(function (resp) {
    var dataBarang = resp.data;
    dataBarang.forEach(function (barang) {
      //
      var optionEl = document.createElement("option");
      optionEl.value = barang.id_barang;
      optionEl.textContent =
        barang.nama_komponen + " | " + barang.kode_inventaris;
      selectBarang.appendChild(optionEl);
    });
  });
}

function removeAllSelectBarangOptions() {
  var options = selectBarang.querySelectorAll("option");
  options.forEach(function (optionEl) {
    optionEl.remove();
  });
}
