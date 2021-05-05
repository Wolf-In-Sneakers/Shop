const like_product = function (id_product) {
    fetch('product.php?id_product=' + id_product, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: "like_product=" + +id_product
    });
    let like_quantity = document.querySelector(".btn_like_value");
    like_quantity.innerText++;
}

const add_in_basket = function (id_product) {
    fetch('basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: "add_in_basket=Добавить товар в корзину" +
            "&id_product=" + +id_product
    });
}

const change_quantity = function (id_product, value) {
    if (value > 0) {
        fetch('basket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
            },
            body: "change_quantity=Изменить количество" +
                "&id_product=" + +id_product +
                "&value=" + +value
        });
        let price = document.querySelector(".basket-item[data-id_product='" + id_product + "'] .item-price");
        price.innerText = price.dataset.price + "руб * " + value + " = " + price.dataset.price * value + "руб";
        total_price();
    } else {
        if (confirm("Вы действительно хотите удалить товар?")) {
            delete_in_basket(id_product);
        } else {
            let quantity = document.querySelector(".basket-item[data-id_product='" + id_product + "'] .basket-quantity");
            quantity.value = 1;
            change_quantity(id_product, 1);
        }
    }
}


const delete_in_basket = function (id_product) {
    fetch('basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: "delete_in_basket=Удалить из корзины" +
            "&id_product=" + id_product
    });
    let delete_item = document.querySelector(".basket-item[data-id_product='" + id_product + "']");
    delete_item.parentElement.removeChild(delete_item);
    let basket_items = document.querySelectorAll(".basket-item");
    if (basket_items.length === 0) {
        let clear_basket = document.querySelector(".basket-items");
        clear_basket.parentElement.removeChild(clear_basket);
    } else {
        total_price();
    }
}

const clear_basket = function () {
    fetch('basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: "clear_basket=Очистить корзину"
    });
    let clear_basket = document.querySelector(".basket-items");
    clear_basket.parentElement.removeChild(clear_basket);

}

const total_price = function () {
    let total = 0;
    let items = document.querySelectorAll(".basket-item");

    items.forEach(item => {
        let quantity = item.childNodes.item(5).value;
        let price = item.childNodes.item(7).childNodes.item(1).dataset.price;
        total += quantity * price;
    });
    let total_price = document.querySelector(".total-price");
    total_price.innerText = "Итого: " + total + "руб";
}

document.addEventListener("click", (event) => {
    if (event.target.classList.contains("btn_like")) {
        like_product(+event.target.dataset.id_product);
    }
});

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
    if (event.target.classList.contains("delete_in_basket")) {
        delete_in_basket(event.target.parentElement.dataset.id_product);
    }
});

document.addEventListener("click", (event) => {
    if (event.target.classList.contains("clear_basket")) {
        clear_basket();
    }
});