// Скрипты для страницы каталога

$(".rm-btn").on("click", (e) => {
    const t = e.target.closest(".product");
    $(t).toggleClass("open")
})
$(".product").on("click", (e) => {
    if (!$(e).attr("original")) {
        e.stopPropagation()
    }
})
$("body").on("click", () => {
    $(".product.open").removeClass("open")
})

$(document).ready(() => {
    const ipCarousel = $("#ip-carousel .carousel-inner");
    if (ipCarousel) {
        $("#catalog .product__gallery [original]").on("click", (e) => {
            const thumbs = $(e.target).closest('.product').find("[original]")
            let slides = thumbs.map((ind, thumb) => {
                return `<div class="carousel-item ${ind === 0 ? "active" : ""}" style="background: url(${$(thumb).attr("original")}) no-repeat center / contain"></div>`
            })
            $("body").addClass("blured")
            $(ipCarousel).html("")
            slides.map((ind, s) => {
                ipCarousel.append(s)
            });
            $("#ip-carousel").css({
                display: "flex"
            })
        })
        $('#ip-carousel .btn-close').on("click", (e) => {
            e.stopPropagation()
            $(ipCarousel).html("")
            $("#ip-carousel").hide()
            $("body").removeClass("blured")
        })
    }
})