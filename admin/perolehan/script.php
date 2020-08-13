<script>
    (function() {
        var fieldHargaBeli = document.body.querySelector("#harga_beli"),
            fieldJumlah = document.body.querySelector("#jumlah"),
            outputTotal = document.body.querySelector("#total");

        fieldHargaBeli.addEventListener("input", countTotal);
        fieldJumlah.addEventListener("input", countTotal);

        function countTotal() {
            var hargaBeli = parseInt(fieldHargaBeli.value),
                jumlah = parseInt(fieldJumlah.value);
            var total = hargaBeli * jumlah
            outputTotal.textContent = isNaN(total) ? '0' : total;
        }

        countTotal();
    })()
</script>
