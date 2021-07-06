const add_in_basket = async function (id_product) {
    let response = await fetch('/basket/add/' + id_product);
    let result = await response.json();

    if (response.status === 200) {

        let cart = document.querySelector(".cart .cart-content-items");
        let cart_item = cart.querySelector(".cart-item[data-id_product='" + result.id_product + "']");

        if (cart_item === null) {
            let article = document.createElement("article");
            article.className = "cart-item";
            article.dataset.id_product = result.id_product;

            article.innerHTML = "<a href='/product/" + result.id_product + "' class='wrapper-img'>" +
                "<img src='" + result.image_name + "' alt='item'>" +
                "</a>" +
                "<div>" +
                "<a href='/product/" + result.id_product + "' class='item-name'>" + result.name + "</a>" +
                "<h4 class='item-price' data-price='" + result.price + "' data-quantity='" + result.quantity + "'>" + result.price + "руб x " + result.quantity + "</h4>" +
                "</div>" +
                "<button class='cart-item-btn-close delete_in_basket'><i class='fa fa-times-circle' aria-hidden='true'></i></button>";

            cart.append(article);
        } else {
            let item_price = cart_item.querySelector(".item-price");
            item_price.dataset.quantity = result.quantity;
            item_price.innerText = result.price + "руб x " + result.quantity;
        }

        total_price();
    } else {
        alert(result);
    }
}

const change_quantity = async function (id_product, quantity) {
    if (quantity > 0) {
        let response = await fetch('/basket/change/' + id_product, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
            },
            body: "quantity=" + +quantity
        });

        let result = await response.json();

        if (response.status === 200) {
            let cart_item = document.querySelector(".cart-item[data-id_product='" + id_product + "'] .item-price");
            cart_item.dataset.quantity = result.quantity;
            cart_item.innerText = cart_item.dataset.price + "руб х " + result.quantity;

            let price = document.querySelector(".basket-item[data-id_product='" + id_product + "'] .item-price");
            price.innerText = price.dataset.price + "руб x " + result.quantity + " = " + price.dataset.price * result.quantity + "руб";

            total_price();
        } else {
            alert(result);
        }
    } else {
        if (confirm("Вы действительно хотите удалить товар?")) {
            delete_in_basket(id_product);
        } else {
            let item = document.querySelector(".basket-item[data-id_product='" + id_product + "'] .basket-quantity");
            item.value = 1;
            change_quantity(id_product, item.value);
        }
    }
}

const delete_in_basket = async function (id_product) {
    let response = await fetch('/basket/delete/' + id_product);

    if (response.status === 200) {
        let cart_item = document.querySelector(".cart-item[data-id_product='" + id_product + "']");
        cart_item.parentElement.removeChild(cart_item);

        let delete_item = document.querySelector(".basket-item[data-id_product='" + id_product + "']");
        if (delete_item) {
            delete_item.parentElement.removeChild(delete_item);

            let basket_items = document.querySelectorAll(".basket-item");
            if (basket_items.length === 0) {
                let clear_basket = document.querySelector(".basket-items");
                clear_basket.parentElement.removeChild(clear_basket);
            }
        }

        total_price();
    } else {
        let result = await response.json();
        alert(result);
    }
}

const clear_basket = async function () {
    console.log(123);
    let response = await fetch('/basket/clear');

    if (response.status === 200) {
        let clear_cart = document.querySelector(".cart-content-items");
        clear_cart.parentElement.removeChild(clear_cart);

        let clear_basket = document.querySelector(".basket-items");
        clear_basket.parentElement.removeChild(clear_basket);

        total_price();
    } else {
        let result = await response.json();
        alert(result);
    }
}

const total_price = function () {
    let total = 0;
    let items_quantity = 0;

    let items = document.querySelectorAll(".cart-item .item-price");

    items.forEach(item => {
        let quantity = item.dataset.quantity;
        let price = item.dataset.price;

        total += quantity * price;
        items_quantity += +quantity;
    });

    let total_price = document.querySelectorAll(".cart-total-value");
    total_price.forEach((item) => {
        item.innerText = +total;
    });

    let total_quantity = document.querySelector(".basket>.circle");
    total_quantity.innerText = +items_quantity;
}


document.addEventListener("click", (event) => {
    if (event.target.classList.contains("add_in_basket")) {
        add_in_basket(+event.target.dataset.id_product);
    }
});

document.addEventListener("change", (event) => {
    if (event.target.classList.contains("basket-quantity")) {
        change_quantity(+event.target.parentElement.dataset.id_product, +event.target.value);
    }
});

document.addEventListener("click", (event) => {
    if (event.target.parentElement.classList.contains("delete_in_basket")) {
        delete_in_basket(+event.target.parentElement.parentElement.dataset.id_product);
    } else if (event.target.classList.contains("delete_in_basket")) {
        delete_in_basket(+event.target.parentElement.dataset.id_product);
    }
});

document.addEventListener("click", (event) => {
    if (event.target.classList.contains("clear_basket")) {
        clear_basket();
    }
});

