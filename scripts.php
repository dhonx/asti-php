<?php require_once "config.php" ?>

<script defer>
    (function() {
        "use strict";
        var mobileMenuBtn = document.body.querySelector("#mobile-menu"),
            navMenu = document.body.querySelector("#nav-menu"),
            main = document.body.querySelector("main");

        mobileMenuBtn.addEventListener("click", function() {
            navMenu.style.display = getComputedStyle(navMenu).display == "none" ? "block" : "none";
            main.classList.toggle("lg:ml-64")
        });
    })();
</script>
