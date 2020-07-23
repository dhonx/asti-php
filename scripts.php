<?php require_once "config.php" ?>

<script defer>
    (function() {
        "use strict";
        var mobileMenuBtn = document.body.querySelector("#mobile-menu"),
            navMenu = document.body.querySelector("#nav-menu"),
            main = document.body.querySelector("main"),
            deleteLinks = document.body.querySelectorAll(".delete-link");

        mobileMenuBtn.addEventListener("click", function() {
            navMenu.classList.toggle("active");
            mobileMenuBtn.querySelector(".mdi").classList.toggle("mdi-close")
            mobileMenuBtn.querySelector(".mdi").classList.toggle("mdi-menu")
        });

        deleteLinks.forEach(function(deleteLink) {
            deleteLink.addEventListener("click", function(event) {
                event.preventDefault();
                var nama = this.dataset.nama;
                if (window.confirm(`Apakah kamu yakin akan menghapus ${nama}?`)) {
                    window.location.assign(this.href);
                }
            })
        })
    })();
</script>
