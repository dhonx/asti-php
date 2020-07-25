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

        window.toggleSidenavExpand = function(el) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                navMenu.classList.toggle("expand");
                handle_sidenav();
            }
            xhr.open("GET", el.dataset.url, true);
            xhr.send();
        }

        function handle_sidenav(el = document.querySelector("#expand-button")) {
            var expanded = navMenu.classList.contains("expand");
            el.querySelector("a").title = el.querySelector(".label").textContent = expanded ? "Perkecil" : "Perluas";
            if (expanded) {
                el.querySelector(".mdi").classList.remove("mdi-chevron-double-right");
                el.querySelector(".mdi").classList.add("mdi-chevron-double-left");
            } else {
                el.querySelector(".mdi").classList.add("mdi-chevron-double-right");
                el.querySelector(".mdi").classList.remove("mdi-chevron-double-left");
            }
        }

        handle_sidenav();

        /** @param {HTMLSelectElement} el */
        window.submit_items_per_page = function(el) {
            var url = window.location.search.includes("&ipp=" + el.value) ? window.location.search : window.location.search + "&ipp=" + el.value;
            window.location.assign(url);
        }

        // function checkDarkMode() {
        //     return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        // }

        // // if (checkDarkMode()) {
        // //     document.documentElement.classList.add('mode-dark');
        // // } else {
        // //     document.documentElement.classList.remove('mode-dark');
        // // }
    })();
</script>
