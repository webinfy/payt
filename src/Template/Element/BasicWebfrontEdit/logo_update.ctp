<div id="webfrontlogo" class="tab-pane fade <?= ($tab == 'webfrontlogo') ? 'active show' : '' ?>">
    <div class="form-div">
        <div class="profile-pic-form-div">
            <?php if (!empty($webfront->logo)) { ?> 
                <div><img src="<?= HTTP_ROOT . WEBFRONT_LOGO . $webfront->logo ?>" /></div><br/>
            <?php } else { ?>
                <div><img src="images/not-available.png" /></div><br/>
            <?php } ?>

            <?= $this->Form->create($webfront, ['type' => 'file']); ?>

            <input type="file" name="logo" id="file-browser" class="text-file"><br><br>
            <div class="form-div">
                <a href="javascript:;" id="cancel-btn" class="button-2 prev-btn">Back</a>
                <input type="submit" value="Update" id="pic-update-btn" name="pic-update-btn" class="button-3">
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

<script>
    $(document).ready(function () {
        $imageDiv = $('.uploaded-img').croppie({
            enableExif: true,
            viewport: {
                width: 250,
                height: 250,
                type: 'rectangular'
            },
            boundary: {
                width: 300,
                height: 300
            },
            enableResize: true
        });

        $('#file-browser').on('change', function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                $imageDiv.croppie('bind', {
                    url: e.target.result
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#pic-update-btn').on('click', function () {
            $imageDiv.croppie('result', {
                size: 'viewport'
            }).then(function (resp) {
                $('#response').val(resp);
            });
        });
    });
</script>