<header class="header">
    <!--logo-->
    <div class="logo"></div>
    <nav class="header-center">
        <a href="#home" class="underline">
            <span>Home</span><svg viewBox="0 0 13 20">
                <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
            </svg>
        </a>
        <a href="#products">
            <span>Product</span><svg viewBox="0 0 13 20">
                <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
            </svg>
        </a>
        <a href="#about">
            <span>About</span><svg viewBox="0 0 13 20">
                <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
            </svg>
        </a>
        <a href="#contact">
            <span>Contact</span><svg viewBox="0 0 13 20">
                <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
            </svg>
        </a>
    </nav>
    <div class="header-right">
        <a href="#search"><i class="fa-solid fa-magnifying-glass"></i></a>
        <a href="#cart"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="#login"><i class="fa-solid fa-user"></i></a>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.header');
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', function() {

            if (window.scrollY > lastScrollY && window.scrollY > 100) {

                header.classList.add('hidden');
            } else {

                header.classList.remove('hidden');
            }

            lastScrollY = window.scrollY;
        });
    });
</script>