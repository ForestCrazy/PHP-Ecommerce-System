<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
?>
    <script>
        var cacheBanner = null;

        function insertBanner(input) {
            if (input.files.length == 1) {
                cacheBanner = input.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-banner').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                console.error('failed to preview payment slip');
            }
        }

        function toggleBannerModal(banner_id = null) {
            $('#bannerModal').modal('show');
            if (banner_id) {
                $('#banner-id').val(banner_id);
                var banner = $('#banner-' + banner_id).attr('src');
                $('#preview-banner').attr('src', banner);
                $('#remove-banner-btn').removeClass('d-none');
                getBannerImage(banner);
                var banner_tier = $('#banner-tier-' + banner_id).text();
                $('#banner-tier').val(banner_tier);
                var banner_alt = $('#banner-alt-' + banner_id).text();
                $('#banner-alt').val(banner_alt);
            } else {
                $('#banner-id').val(0);
            }
        }

        function updateBanner() {
            var banner_id = $('#banner-id').val();
            var banner_tier = $('#banner-tier').val();
            var banner_alt = $('#banner-alt').val();
            if (cacheBanner || banner_id === 0) {
                var formData = new FormData();
                formData.append('banner_id', banner_id);
                formData.append('banner_tier', banner_tier);
                formData.append('banner', cacheBanner);
                formData.append('banner_alt', banner_alt);
                $.ajax({
                    url: banner_id == 0 ? '/API/createBanner.php' : '/API/updateBanner.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        var resp = JSON.parse(res);
                        if (resp.success) {
                            $('#bannerModal').modal('hide');
                            location.reload();
                        } else {
                            console.error(resp.reason ? resp.reason : 'failed to update or create banner');
                        }
                    }
                })
            } else {
                console.error('cache banner is null');
            }
        }

        function removeBanner() {
            var banner_id = $('#banner-id').val();
            $.get('/API/deleteBanner.php', {
                banner_id: banner_id
            }, function(res) {
                var resp = JSON.parse(res);
                if (resp.success) {
                    $('#bannerModal').modal('hide');
                    location.reload();
                } else {
                    console.error(resp.reason ? resp.reason : 'failed to delete banner');
                }
            })
        }

        function getBannerImage(url) {
            fetch(url).then(async response => {
                const contentType = response.headers.get('content-type')
                const blob = await response.blob()
                const fileName = url.split('/').pop();
                const file = new File([blob], fileName.substring(fileName.indexOf('-') + 1), {
                    contentType
                })
                cacheBanner = file;
            })
        }
    </script>
    <div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="bannerLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannerLabel">จัดการแบนเนอร์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='col col-top font-weight-bold text-center'>
                        <div id='transfer-amount'></div>
                        <div class='text-center'>
                            <img id='preview-banner' class='col' style='min-height: 10rem; max-height: 280px;' src='' />
                        </div>
                        <div class='d-block'>
                            <div class='d-flex justify-content-center'>
                                <div class='btn btn-outline-primary d-block' onclick="$('#insertBanner').click();">เลือกรูป</div>
                                <input type='file' class='d-none' id='insertBanner' onchange='insertBanner(this)' accept=".png, .jpg, .jpeg" />
                            </div>
                            <input type='hidden' id='banner-id' />
                        </div>
                        <select class="browser-default custom-select col-top" id='banner-tier'>
                            <option selected>Tier</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                        <div class='form-group col-top'>
                            <label>Banner Alt</label>
                            <input type='text' id='banner-alt' class='form-control' />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-danger d-none" id="remove-banner-btn" onclick='removeBanner()'>ลบ</button>
                    <button type="button" class="btn btn-primary" onclick='updateBanner()'>บันทึก</button>
                </div>
            </div>
        </div>
    </div>
    <div class='text-right'>
        <div class='btn btn-success col-md-3' onclick='toggleBannerModal()'>เพิ่มแบนเนอร์</div>
    </div>
    <?php
    for ($i = 0; $i < 3; $i++) {
        $sql_banner = 'SELECT * FROM banner WHERE banner_tier = "' . $i . '" ORDER BY banner_id';
        $res_banner = mysqli_query($connect, $sql_banner);
        if ($res_banner) {
    ?>
            <span class='font-weight-bold'>Banner Tier <?= $i ?></span>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tier</th>
                            <th scope="col">Banner</th>
                            <th scope="col">Alt</th>
                            <th scope="col">Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($fetch_banner = mysqli_fetch_assoc($res_banner)) {
                        ?>
                            <tr>
                                <th scope="row"><?= $fetch_banner['banner_id'] ?></th>
                                <td id='banner-tier-<?= $fetch_banner['banner_id'] ?>'><?= $fetch_banner['banner_tier'] ?></td>
                                <td><img id='banner-<?= $fetch_banner['banner_id'] ?>' style='max-height: 100px;' src='<?= $fetch_banner['banner_img'] ?>' /></td>
                                <td id='banner-alt-<?= $fetch_banner['banner_id'] ?>'><?= $fetch_banner['banner_alt'] ?></td>
                                <td>
                                    <div class='btn btn-primary' onclick='toggleBannerModal(<?= $fetch_banner['banner_id'] ?>)'><i class="fas fa-cog"></i></div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
<?php
        }
    }
}
