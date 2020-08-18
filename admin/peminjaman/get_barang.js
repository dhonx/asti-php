var selectKomponen = document.body.querySelector("#komponen"),
  selectBarang = document.body.querySelector("#barang"),
  labelSelectBarang = document.body.querySelector("#label-select-barang");

getBarang();

selectKomponen.addEventListener("input", getBarang);

function getBarang() {
  var idKomponen = selectKomponen.value;
  updateSelectBarangLabel();
  removeAllSelectBarangOptions();
  axios.get("get_barang?id=" + idKomponen).then(function (resp) {
    var dataBarang = resp.data;
    dataBarang.forEach(function (barang) {
      var optionEl = document.createElement("option");
      optionEl.value = barang.id_barang;
      optionEl.textContent = barang.nama_komponen + " | " + barang.kode_inventaris;
      selectBarang.prepend(optionEl);
    });
  });
}

function updateSelectBarangLabel() {
  labelSelectBarang.textContent = "Daftar barang dari komponen " + selectKomponen.selectedOptions[0].textContent;
}

function removeAllSelectBarangOptions() {
  var options = selectBarang.querySelectorAll("option");
  options.forEach(function (optionEl) {
    optionEl.remove();
  });
}
