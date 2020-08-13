var selectKomponen = document.body.querySelector("#id_komponen"),
  selectPerolehan = document.body.querySelector("#id_perolehan"),
  dataPerolehan = null;

getPerolehan();

selectKomponen.addEventListener("input", getPerolehan);

function getPerolehan() {
  var idPerolehan = selectKomponen.value;
  removeAllSelectPerolehanOptions();
  axios
    .get("get_perolehan?id=" + idPerolehan)
    .then(function (res) {
      dataPerolehan = res.data;
      dataPerolehan.forEach(function (perolehan) {
        var optionEl = document.createElement("option");
        optionEl.value = perolehan.id_perolehan;
        optionEl.textContent =
          "Perolehan " +
          perolehan.nama +
          " pada tanggal " +
          perolehan.tanggal +
          " dengan status " +
          perolehan.status +
          " | Jumlah: " +
          perolehan.jumlah;
        selectPerolehan.appendChild(optionEl);
      });
    })
    .catch(function (err) {
      removeAllSelectPerolehanOptions();
    });
}

function removeAllSelectPerolehanOptions() {
  var options = selectPerolehan.querySelectorAll("option");
  options.forEach(function (optionEl) {
    optionEl.remove();
  });
}
