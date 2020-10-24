(function ($, window, document, undefined) {
    $(document).ready(function () {
        $('.vs7_bestseller-list').owlCarousel({
            items: 6,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            nav: true,
            navText: ["", ""],
            dots: true,
            lazyLoad: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 4
                }
            }
        });
    });
}(jQuery, window, document));