function addFavoriteItem(p_id, alert = true) {
    $.get(
        "API/addFavoriteItem.php", {
            p_id: p_id,
        },
        function(res) {
            var resp = JSON.parse(res);
            if (resp["success"] == true) {
                if (alert) {
                    Swal.fire("เพิ่มเป็นสินค้าที่ชื่นชอบสำเร็จ", "", "success");
                } else {
                    console.log("add to favorite item success.");
                }
            } else {
                if (alert) {
                    Swal.fire(
                        "เกิดข้อผิดพลาดในการเพิ่มเป็นสินค้าที่ชื่นชอบ",
                        resp["reason"] ? resp["reason"] : "",
                        "error"
                    );
                } else {
                    console.error(
                        resp["reason"] ?
                        resp["reason"] :
                        "failed to add item to favorite item."
                    );
                }
            }
        }
    );
}

function removeFavoriteItem(p_id) {
    $.get(
        "API/removeFavoriteItem.php", {
            p_id: p_id,
        },
        function(res) {
            var resp = JSON.parse(res);
            if (resp["success"] == true) {
                Swal.fire("ลบออกจากสินค้าที่ชื่นชอบสำเร็จ", "", "success").then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire(
                    "เกิดข้อผิดพลาดในการลบออกจากสินค้าที่ชื่นชอบ",
                    "",
                    "error"
                ).then(() => {
                    window.location.reload();
                });
            }
        }
    );
}

function removeItemFromCart(p_id, alert = true) {
    $.get(
        "API/removeItemFromCart.php", {
            p_id: p_id,
        },
        function(res) {
            var resp = JSON.parse(res);
            if (resp["success"] == true) {
                if (alert) {
                    Swal.fire("ลบสินค้าออกจากตระกร้าสำเร็จ", "", "success");
                } else {
                    console.log("remove item from cart success.");
                }
                var store_id = $("#product-" + p_id).data("storeid");
                document.getElementById("product-" + p_id).remove();
                updateOrderDetail();
                updateItemInCart();
                if ($("div").find('[data-storeid="' + store_id + '"]').length == 0) {
                    $("#store-" + store_id).remove();
                }
            } else {
                if (alert) {
                    Swal.fire(
                        "เกิดข้อผิดพลาดในการลบสินค้าออกจากตระกร้า",
                        "",
                        "error"
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    console.error("error when remove item from cart.");
                }
            }
        }
    );
}

function checkoutFromCart() {
    var productSelect = $("input.productSelect:checked").map(function() {
        return $(this).val();
    });
    productSelect = productSelect.get();
    if (productSelect.length > 0) {
        $.post("API/generateCartTemp.php", {
            p_id: productSelect,
        }).then((res) => {
            try {
                var resp = JSON.parse(res);
                if (resp["success"] == true) {
                    window.location.href = "?page=checkout&cart_id=" + resp["cart_token"];
                } else {
                    Swal.fire(
                        "เกิดข้อผิดพลาดในการสั่งซื้อสินค้า",
                        resp["reason"] ? resp["reason"] : "",
                        "error"
                    );
                }
            } catch (e) {
                Swal.fire(
                    "เกิดข้อผิดพลาดในการสั่งซื้อสินค้า",
                    "เกิดข้อผิดพลาดไม่ทราบสาเหตุ",
                    "error"
                );
            }
        });
    } else {
        Swal.fire(
            "เกิดข้อผิดพลาดในการสั่งซื้อสินค้า",
            "กรุณาเลือกสินค้าก่อนทำการสั่งซื้อ",
            "error"
        );
    }
}

function selectAllItem(checked) {
    if (checked) {
        $("input.productSelect").not(this).prop("checked", true);
    } else {
        $("input.productSelect").not(this).prop("checked", false);
    }
}

function changeItemQuantity(p_id, operator) {
    // var def_val = 0;
    // var cur_val = def_val = parseInt(document.getElementById('product-qty-' + p_id).value);
    // if (cur_val == 1 && operator == '-') {
    //     removeItemFromCart(p_id);
    // } else {
    //     if (cur_val > 0) {
    //         if (operator == '+') {
    //             if (cur_val < max_val) {
    //                 cur_val += 1;
    //                 document.getElementById('product-qty-' + p_id).value = cur_val;
    //             }
    //         } else {
    //             cur_val -= 1;
    //             document.getElementById('product-qty-' + p_id).value = cur_val;
    //         }
    //     }
    // }
    if (
        document.getElementById("product-qty-" + p_id).value <= 1 &&
        operator == "-"
    ) {
        Swal.fire({
            title: "คุณแน่ใจว่าต้องการลบหรือไม่?",
            text: document.getElementById("product-name-" + p_id).textContent,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ใช่",
            cancelButtonText: "ไม่",
        }).then((result) => {
            if (result.isConfirmed) {
                removeItemFromCart(p_id);
            }
        });
    } else {
        $.get("API/updateItemCartQty.php", {
            p_id: p_id,
            qty: document.getElementById("product-qty-" + p_id).value,
            operator: operator,
        }).then((res) => {
            var resp = JSON.parse(res);
            if (resp["success"] == true) {
                if (resp["code"] == 204) {
                    document.getElementById("product-" + p_id).remove();
                } else {
                    updateItemInfo(p_id);
                    updateItemInCart();
                }
            } else {
                if (resp.code == 10100) {
                    Swal.fire(resp.reason);
                }
                console.error(
                    resp.reason ?
                    resp.reason :
                    "error when change product quantity in cart"
                );
            }

            if (resp.productQty) {
                document.getElementById("product-qty-" + p_id).value =
                    resp["itemInCartQty"];
                document.getElementById("max-product-qty-" + p_id).value =
                    resp.productQty;
                if (resp.productQty < resp.itemInCartQty) {
                    $("#remain-product-qty-" + p_id).removeClass("d-none");
                    $("#remain-product-qty-" + p_id).text(
                        "เหลือสินค้าอยู่ " + resp.productQty + " ชิ้น"
                    );
                    $("#checkbox-product-" + p_id).prop("disabled", true);
                } else {
                    $("#remain-product-qty-" + p_id).addClass("d-none");
                    $("#checkbox-product-" + p_id).prop("disabled", false);
                }
            }
            updateOrderDetail();
        });
    }
}

function updateItemInfo(p_id) {
    var product_price = parseInt(
        document.getElementById("product-price-" + p_id).textContent
    );
    document.getElementById("all-price-product-" + p_id).textContent =
        product_price * document.getElementById("product-qty-" + p_id).value;
}

function updateOrderDetail() {
    document.getElementById("count-product-select").textContent = $(
        "input.productSelect:checkbox:checked"
    ).length;
    var productSelect = $("input.productSelect:checked")
        .map(function() {
            return $(this).val();
        })
        .get();
    var all_order_price = 0;
    productSelect.map((index) => {
        all_order_price += parseInt(
            document.getElementById("all-price-product-" + index).textContent
        );
    });
    document.getElementById("all-product-price").textContent =
        "฿" + all_order_price.toString();
}

function removeSelectItemFromCart() {
    var productSelect = $("input.productSelect:checked")
        .map(function() {
            return $(this).val();
        })
        .get();
    if (productSelect.length > 0) {
        Swal.fire({
            title: "คุณแน่ใจว่าต้องการลบหรือไม่?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ใช่",
            cancelButtonText: "ไม่",
        }).then((result) => {
            if (result.isConfirmed) {
                productSelect.map((index) => {
                    removeItemFromCart(index, false);
                });
                updateItemInCart();
            }
        });
    } else {
        Swal.fire("เกิดข้อผิดลาด", "กรุณาเลือกสินค้าก่อนลบสินค้า", "error");
    }
}

function addFavoriteSelectItemFromCart() {
    var productSelect = $("input.productSelect:checked")
        .map(function() {
            return $(this).val();
        })
        .get();
    if (productSelect.length > 0) {
        Swal.fire({
            title: "เมื่อเพิ่มสินค้าในรายการสินค้าที่ชอบแล้ว ต้องการนำสินค้าที่เลือกออกจากตะกร้าสินค้าด้วยหรือไม่?",
            text: "",
            icon: "warning",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ใช่",
            denyButtonText: "ไม่",
            cancelButtonText: "ยกเลิก",
        }).then((result) => {
            if (result.isConfirmed) {
                productSelect.map((index) => {
                    addFavoriteItem(index, false);
                    removeItemFromCart(index, false);
                });
                updateItemInCart();
            } else if (result.isDenied) {
                productSelect.map((index) => {
                    addFavoriteItem(index, false);
                });
            }
        });
    } else {
        Swal.fire(
            "เกิดข้อผิดลาด",
            "กรุณาเลือกสินค้าก่อนเพิ่มเป็นสินค้าที่ชอบ",
            "error"
        );
    }
}

function updateItemInCart() {
    $.get("API/itemInCart.php").then((res) => {
        var resp = JSON.parse(res);
        if (resp["success"] == true) {
            document.getElementById("item-in-cart").textContent =
                resp["amountItemInCart"];
        } else {
            document.getElementById("item-in-cart").textContent = 0;
        }
    });
}