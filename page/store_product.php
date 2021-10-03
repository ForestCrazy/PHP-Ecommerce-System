<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    if (isset($_GET['action'])) {
?>
        <script>
            var productId = new URL(window.location.href).searchParams.get('p_id');
            var productImage = [];
            var productImageNodeList = null;
            $(document).ready(function() {
                //Initialize tooltips
                $('.nav-tabs > li a[title]').tooltip();

                //Wizard
                $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                    var $target = $(e.target);
                    if ($target.parent().hasClass('disabled')) {
                        return false;
                    }
                });

                $(".next-step").click(function(e) {
                    var $active = $('.nav-tabs li>.active');
                    $active.parent().next().find('.nav-link').removeClass('disabled');
                    nextTab($active);
                });

                $(".prev-step").click(function(e) {
                    var $active = $('.nav-tabs li>a.active');
                    prevTab($active);
                });
            });

            function nextTab(elem) {
                $(elem).parent().next().find('a[data-toggle="tab"]').click();
            }

            function prevTab(elem) {
                $(elem).parent().prev().find('a[data-toggle="tab"]').click();
            }

            function readFile(input) {
                counter = input.files.length;
                for (x = 0; x < counter; x++) {
                    productImage.push(input.files[x]);
                    productImage[x].id = productImage.length - 1;
                }
                displayPreviewFile();
                clearFileListLabel();
            }

            function displayPreviewFile() {
                $('#product-preview-img-list').html('');

                function FileReaderImage(fileItem) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = ((file) => {
                            return (e) => {
                                resolve('<div class="col-md-3 col-sm-3 col-xs-3 img-product-box draggable" draggable="true"><span class="remove-img-product" data-index="' + productImage.indexOf(file) + '" onclick="removeImgProductFromList(' + productImage.indexOf(file) + ')"><i class="fa fa-times"></i></span><img src="' + e.target.result + '" class="img-thumbnail"></div>');
                            }
                        })(fileItem);
                        reader.readAsDataURL(fileItem);
                    })
                }
                Promise.all(productImage.map(item => FileReaderImage(item))).then((productImgElementList) => {
                    productImgElementList.forEach((item) => {
                        $('#product-preview-img-list').append(item);
                    })
                    productImageNodeList = document.querySelectorAll('.container .draggable');
                    productImageNodeList.forEach(function(item) {
                        item.addEventListener('dragstart', handleDragStart, false);
                        item.addEventListener('dragenter', handleDragEnter, false);
                        item.addEventListener('dragover', handleDragOver, false);
                        item.addEventListener('dragleave', handleDragLeave, false);
                        item.addEventListener('drop', handleDrop, false);
                        item.addEventListener('dragend', handleDragEnd, false);
                    });
                }).catch((error) => {
                    console.error(error);
                })
            }

            function clearFileListLabel() {
                setTimeout(() => {
                    $('#file-label').text('Add Product Image..');
                }, 1000);
            }

            function removeImgProductFromList(id) {
                productImage.splice(id, 1);
                displayPreviewFile();

            }

            function submitAddProduct() {
                var productName = $('#product-name').val();
                var productPrice = $('#product-price').val();
                var productDescription = quill.container.firstChild.innerHTML;
                var productQty = $('#product-qty').val();

                var form_data = new FormData();
                form_data.append('productName', productName);
                form_data.append('productPrice', productPrice);
                form_data.append('productDescription', productDescription);
                form_data.append('productQty', productQty);
                $.ajax({
                    url: '/API/createProductStore.php',
                    data: form_data,
                    type: 'post',
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        var resp = JSON.parse(res);
                        if (resp.success) {
                            updateProductImage(resp.productId);
                            updateProductShipping(resp.productId);
                            console.log('create product to store success, product id is', resp.productId);
                        } else {
                            console.error(resp.reason ? resp.reason : 'failed to create product item');
                        }
                    }
                })
            }

            function submitUpdateProduct() {
                var productId = new URL(window.location.href).searchParams.get('p_id');
                var productName = $('#product-name').val();
                var productPrice = $('#product-price').val();
                var productDescription = quill.container.firstChild.innerHTML;
                var productQty = $('#product-qty').val();

                var form_data = new FormData();
                form_data.append('productId', productId);
                form_data.append('productName', productName);
                form_data.append('productPrice', productPrice);
                form_data.append('productDescription', productDescription);
                form_data.append('productQty', productQty);
                $.ajax({
                    url: '/API/updateProductDetail.php',
                    data: form_data,
                    type: 'post',
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        var resp = JSON.parse(res);
                        if (resp.success) {
                            updateProductImage();
                            updateProductShipping();
                        }
                    }
                })
            }

            function updateProductImage(p_id) {
                var form_data = new FormData();
                form_data.append('productId', p_id ? p_id : new URL(window.location.href).searchParams.get('p_id'));
                for (i = 0; i < productImage.length; i++) {
                    form_data.append('productImage[]', productImage[i]);
                }
                $.ajax({
                    url: '/API/updateImgProduct.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(res) {
                        var resp = JSON.parse(res);
                        if (resp.success) {
                            console.log('update product image success');
                            $('#manage-product-loading').toggleClass('d-none');
                            $('#manage-product-result').toggleClass('d-none');
                        } else {
                            console.error(resp.reason ? resp.reason : 'failed to add new product to store');
                        }
                    }
                })
            }

            function updateProductShipping(p_id) {
                var accessUpdateProductShipping = true;

                if (accessUpdateProductShipping) {
                    var shippingProviderSelect = $('input.shipping-provider[type=checkbox]:checked').map(function() {
                        return $(this).val();
                    });
                    shippingProviderSelect = shippingProviderSelect.get();
                    var shippingPrice = [];
                    var shippingTime = [];

                    if (shippingProviderSelect.length > 0) {
                        shippingProviderSelect.map(key => {
                            shippingPrice.push($('#shipping-price-' + key).val());
                            shippingTime.push($('#shipping-time-' + key).val());
                        })
                        if (shippingPrice.length > 0) {
                            $.post('/API/updateProductShipping.php', {
                                p_id: p_id ? p_id : new URL(window.location.href).searchParams.get('p_id'),
                                shippingProvider: shippingProviderSelect,
                                shippingPrice: shippingPrice,
                                shippingTime: shippingTime,
                                test: '',
                            }, function(res) {
                                var resp = JSON.parse(res);
                                if (resp.success) {
                                    console.log('update product shipping success');
                                } else {
                                    console.error(resp.reason ? resp.reason : 'failed to update product shipping');
                                }
                            });
                        }
                    }
                    console.log(shippingProviderSelect, shippingPrice, shippingTime);
                }
            }

            function shippingProviderConfig(p_id, data = {}) {
                $('input.shipping-provider[value="' + p_id + '"]').prop('checked', true);
                $('#shipping-id-' + p_id).toggleClass('d-none');
                $('#shipping-price-' + p_id).val(data.price ? data.price : '');
                $('#shipping-time-' + p_id).val(data.timeShipping ? data.timeShipping : '');
            }

            $(document).ready(function() {
                $("input.shipping-provider[type=checkbox]").change(function() {
                    shippingProviderConfig(this.value);
                });

                if (new URL(window.location.href).searchParams.get('p_id')) {
                    getProductImage();
                    getProductShipping();
                }
            });

            function getProductImage() {
                function createFileObject(url) {
                    return new Promise((resolve, reject) => {
                        fetch(url).then(async response => {
                            const contentType = response.headers.get('content-type')
                            const blob = await response.blob()
                            const fileName = url.split('/').pop();
                            const file = new File([blob], fileName.substring(fileName.indexOf('-') + 1), {
                                contentType
                            })
                            resolve(file);
                        })
                    })
                }
                $.get('/API/imageProduct.php', {
                    p_id: new URL(window.location.href).searchParams.get('p_id')
                }, function(res) {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        Promise.all(resp.data.map(key => createFileObject(key.img_url))).then((files) => {
                            productImage = files;
                            displayPreviewFile();
                        })
                    }
                })
            }

            var dragSrcEl = null;

            function handleDragStart(e) {
                this.style.opacity = '0.4';

                dragSrcEl = this;

                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            }

            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }

                e.dataTransfer.dropEffect = 'move';

                return false;
            }

            function handleDragEnter(e) {
                this.classList.add('over');
            }

            function handleDragLeave(e) {
                this.classList.remove('over');
            }

            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation(); // stops the browser from redirecting.
                }

                if (dragSrcEl != this) {
                    var beforeIndex = $('.remove-img-product', '<div>' + dragSrcEl.innerHTML + '</div>').data('index');
                    const fileObj = productImage[beforeIndex];
                    console.log(fileObj);
                    productImage.splice(beforeIndex, 1);
                    productImage.splice($(this).index(), 0, fileObj);
                    displayPreviewFile();
                }

                resetOpacityEffectDraggable();

                return false;
            }

            function handleDragEnd(e) {
                resetOpacityEffectDraggable();
            }

            function resetOpacityEffectDraggable() {
                productImageNodeList.forEach(function(item) {
                    item.classList.remove('over');
                    item.style.opacity = '1';
                });
            }

            function getProductShipping(p_id) {
                $.get('/API/productShippingProvider.php', {
                    p_id: p_id ? p_id : new URL(window.location.href).searchParams.get('p_id')
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        resp.data.map((item) => {
                            shippingProviderConfig(item.shipping_provider_id, {
                                price: item.shipping_price,
                                timeShipping: item.shipping_time
                            });
                        })
                    } else {
                        console.error('failed to get shipping detail');
                    }
                })
            }

            function deleteProduct() {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่ว่าต้องการลบสินค้า?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/API/deleteProduct.php', {
                            p_id: productId
                        }).then((res) => {
                            var resp = JSON.parse(res);
                            if (resp.success) {
                                Swal.fire(
                                    'ลบสินค้าสำเร็จ',
                                    '',
                                    'success'
                                ).then(() => {
                                    window.location.href = '?page=store_product';
                                })
                            }
                        })

                    }
                })

            }
        </script>
        <style>
            .editor-control strong {
                font-weight: 800 !important;
            }

            .editor-control {
                background-color: white !important;
            }

            .img-thumbnail {
                width: 100%;
                height: 10rem;
                object-fit: cover;
                object-position: center;
                margin: 10px;
            }

            .img-product-box {
                position: relative;
            }

            .remove-img-product {
                right: 0px;
                position: absolute;
            }

            .draggable img {
                cursor: move;
            }

            .draggable.over {
                border: 3px dotted #666;
            }

            [draggable] {
                user-select: none;
            }

            .draggable img {
                pointer-events: none;
            }
        </style>
        <?php
        if ($_GET['action'] == 'edit') {
            $sql_edit_product = 'SELECT * FROM product WHERE product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
            $res_edit_product = mysqli_query($connect, $sql_edit_product);
            if ($res_edit_product) {
                $fetch_edit_product = mysqli_fetch_assoc($res_edit_product);
            } else {
                gotoPage('store_product');
            }
        }
        ?>
        <div class="container py-3 my-3 border rounded shadow-sm">
            <div class="row">
                <section class="col-12">
                    <ul class="nav nav-tabs flex-nowrap" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="ข้อมูลสินค้า"> 1 </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab" title="ภาพสินค้า"> 2 </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab" title="การจัดส่ง"> 3 </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#complete" class="nav-link disabled" data-toggle="tab" aria-controls="complete" role="tab" title="สำเร็จ"> 4 </a>
                        </li>
                    </ul>
                    <form role="form">
                        <div class="tab-content py-2">
                            <div class="tab-pane active" role="tabpanel" id="step1">
                                <h3>ข้อมูลสินค้า</h3>
                                <div class='col'>
                                    <div class='form-group col'>
                                        <label>ชื่อสินค้า</label>
                                        <input type='text' name='product-name' id='product-name' class='form-control' value='<?= isset($fetch_edit_product) ? $fetch_edit_product['product_name'] : "" ?>' />
                                    </div>
                                    <div class='row col pr-0'>
                                        <div class='form-group col-sm-4 pr-0'>
                                            <label>ราคา</label>
                                            <input type='number' name='product-price' id='product-price' class='form-control' min='0' value='<?= isset($fetch_edit_product) ? $fetch_edit_product['product_price'] : "" ?>' />
                                        </div>
                                        <div class='form-group col-sm-4 pr-0'>
                                            <label>จำนวนสินค้า</label>
                                            <input type='number' name='product-qty' id='product-qty' class='form-control' min='0' value='<?= isset($fetch_edit_product) ? $fetch_edit_product['product_quantity'] : "" ?>' />
                                        </div>
                                        <!-- <div class='form-group col-sm-4 pr-0'>
                                                <label>หมวดหมู่สินค้า</label>
                                                <select class="browser-default custom-select" name='product-price' id='product-price'>
                                                    <option selected>เลือกหมวดหมู่สินค้า</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div> -->
                                    </div>
                                    <div class='form-group col'>
                                        <label>รายละเอียดสินค้า</label>
                                        <div id="editor" class='editor-control'>
                                            <?= isset($fetch_edit_product) ? $fetch_edit_product['product_description'] : "" ?>
                                        </div>
                                    </div>
                                </div>
                                <ul class="float-right">
                                    <li class="list-inline-item">
                                        <button type="button" class="btn btn-primary next-step">Next</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane" role="tabpanel" id="step2">
                                <h3>ภาพสินค้า</h3>
                                <div class='col'>
                                    <div class='col custom-file'>
                                        <input type="file" class="custom-file-input" name="product-img[]" id="product-img" accept=".png, .jpg, .jpeg" onchange="readFile(this);" multiple>
                                        <label class='custom-file-label' id='file-label'>Add Product Image..</label>
                                    </div>
                                    <div class='col'>
                                        <div id="product-preview-img-list" class="row"></div>
                                    </div>
                                </div>
                                <ul class="float-right">
                                    <li class="list-inline-item">
                                        <button type="button" class="btn btn-outline-primary prev-step">Previous</button>
                                    </li>
                                    <li class="list-inline-item">
                                        <button type="button" class="btn btn-primary next-step">Next</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane" role="tabpanel" id="step3">
                                <h3>การจัดส่ง</h3>
                                <div class='col'>
                                    <?php
                                    $sql_shipping_provider = 'SELECT * FROM shipping_provider';
                                    $res_shipping_provider = mysqli_query($connect, $sql_shipping_provider);
                                    if ($res_shipping_provider) {
                                        while ($fetch_shipping_provider = mysqli_fetch_assoc($res_shipping_provider)) {
                                    ?>
                                            <div class="custom-checkbox">
                                                <input type="checkbox" class='shipping-provider' name='shipping[]' value='<?= $fetch_shipping_provider['shipping_provider_id'] ?>'>
                                                <span><?= $fetch_shipping_provider['shipping_name'] ?></span>
                                            </div>
                                            <div id='shipping-id-<?= $fetch_shipping_provider['shipping_provider_id'] ?>' class='row d-none'>
                                                <div class='form-group col-sm-6 pr-0'>
                                                    <label>ค่าจัดส่ง</label>
                                                    <input type='number' name='shipping-price-<?= $fetch_shipping_provider['shipping_provider_id'] ?>' id='shipping-price-<?= $fetch_shipping_provider['shipping_provider_id'] ?>' class='form-control shipping-provider' min='0' />
                                                </div>
                                                <div class='form-group col-sm-6 pr-0'>
                                                    <label>ระยะเวลาในการจัดส่ง</label>
                                                    <input type='text' name='shipping-time-<?= $fetch_shipping_provider['shipping_provider_id'] ?>' id='shipping-time-<?= $fetch_shipping_provider['shipping_provider_id'] ?>' class='form-control shipping-provider' placeholder='เช่น 1-2 3-7 8-9' />
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <ul class="float-right">
                                    <li class="list-inline-item">
                                        <button type="button" class="btn btn-outline-primary prev-step">Previous</button>
                                    </li>
                                    <li class="list-inline-item">
                                        <button type="button" id='confirm-add-product' onclick='productId ? submitUpdateProduct() : submitAddProduct()' class="btn btn-primary btn-info-full next-step">Save and continue</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane" role="tabpanel" id="complete">
                                <div id='manage-product-loading'>
                                    <div class="spinner-border" role="status">

                                    </div>
                                    <span id='ManageStatus'><?= isset($_GET['p_id']) ? 'กำลังอัพเดทข้อมูลสินค้า' : 'กำลังสร้างสินค้า' ?> กรุณารอสักครู่...</span>
                                </div>
                                <div id='manage-product-result' class='d-none'>
                                    <h3><?= isset($_GET['p_id']) ? 'อัพเดทข้อมูลสินค้าสำเร็จ' : 'เพิ่มสินค้าสำเร็จ' ?></h3>
                                    <!-- <p>ไปหน้าสินค้า</p> -->
                                    <a href='?page=store_product'>
                                        <p>กลับไปหน้าจัดการสินค้า</p>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </section>
            </div>
            <script>
                var quill = new Quill('#editor', {
                    theme: 'snow'
                });
            </script>
        </div>
    <?php
    } else {
    ?>
        <div class='text-right'>
            <a href='?page=store_product&action=create'>
                <button class='btn btn-success'>
                    เพิ่มสินค้า
                </button>
            </a>
        </div>
        <?php
        $sql_store_product = 'SELECT product.*, product_img.img_url FROM product INNER JOIN store ON product.store_id = store.store_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE store.u_id = "' . $_SESSION['u_id'] . '"';
        $res_store_product = mysqli_query($connect, $sql_store_product);
        if ($res_store_product) {
            while ($fetch_store_product = mysqli_fetch_assoc($res_store_product)) {
        ?>
                <div id='product-<?= $fetch_store_product['product_id'] ?>' class='col card col-top'>
                    <div class="h-100 d-flex justify-content-start align-items-center">
                        <img src='<?= $fetch_store_product['img_url'] ?>' style="max-height: 120px; max-width: 120px;" alt="" class="img-fluid" />
                        <div class='flex-fill'>
                            <div class='row' style='margin-left: 0px!important; margin-right: 0px!important;'>
                                <div class='col-10'>
                                    <a href='?page=product&p_id=<?= $fetch_store_product['product_id'] ?>' class='text-dark'>
                                        <h5 id='product-name-<?= $fetch_store_product['product_id'] ?>' class='card-title'><?= $fetch_store_product['product_name'] ?></h5>
                                    </a>
                                    <div class='row text-md-center'>
                                        <div class='col-sm-4'>
                                            <span class='d-inline d-md-block'>
                                                ราคาต่อชิ้น
                                            </span>
                                            <span class='d-inline d-md-none'> :</span>
                                            <span id='product-price-<?= $fetch_store_product['product_id'] ?>' class='d-inline d-md-block'>
                                                <?= $fetch_store_product['product_price'] ?>
                                            </span>
                                        </div>
                                        <div class='col-8 col-sm-4'>
                                            <span class='d-inline d-md-block'>จำนวนสินค้าที่เหลือ</span>
                                            <span class='d-inline d-md-none'> : </span>
                                            <div class='d-flex justify-content-md-center'>
                                                <?= $fetch_store_product['product_quantity'] ?>
                                            </div>
                                        </div>
                                        <div class='col-8 col-sm-4'>
                                            <span class='d-inline d-md-block'>
                                                ลงสินค้าเมื่อ
                                            </span>
                                            <span class='d-inline d-md-none'>
                                                :
                                            </span>
                                            <span class='d-inline d-md-block'>
                                                <?= date('d-m-Y', strtotime($fetch_store_product['createtime'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-2'>
                                    <div class='float-right'>
                                        <a href='?page=store_product&action=edit&p_id=<?= $fetch_store_product['product_id'] ?>'>
                                            <span class="btn btn-danger d-none d-sm-block waves-effect waves-light">
                                                แก้ไข
                                            </span>
                                            <span class="d-block d-sm-none remove-btn-text">
                                                แก้ไข
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
            }
        }
    }
}
