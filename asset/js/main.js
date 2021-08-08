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
                    console.error(resp['reason'] ? resp['reason'] : "failed to add item to favorite item.");
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