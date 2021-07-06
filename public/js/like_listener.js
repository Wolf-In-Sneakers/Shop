const like_product = async function (id_product) {
    let response = await fetch('/product/like/' + id_product);

    if (response.status === 200) {
        let like_quantity = document.querySelector(".btn_like_value");
        like_quantity.innerText++;
    }
}

document.addEventListener("click", (event) => {
    if (event.target.classList.contains("btn_like")) {
        like_product(+event.target.dataset.id_product);
    }
});