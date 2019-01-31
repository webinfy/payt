<!-- Update Logo Section Start -->
<div id="profilepicture" class="tab-pane fade <?= ($tab == 'profilepicture') ? "active show" : "" ?>">
    <!--    <div class="img-upload-div">
            <div class="uploaded-img"></div>
        </div>-->
    <div class="profile-pic-form-div">
        <?php if (!empty($webfront->logo)) { ?> 
            <div><img src="<?= HTTP_ROOT . WEBFRONT_LOGO . $webfront->logo ?>" /></div><br/>
        <?php } else { ?>
            <div><img src="images/not-available.png" /></div><br/>
        <?php } ?>
        <?= $this->Form->create($webfront, ['type' => 'file', 'id' => 'update-logo-form']); ?>
        <input type="hidden" name="step" value="logo" />
        <input type="file" name="logo" required id="file-browser" class="text-file"><br><br>        

        <!--<input type="hidden" name="logobase64" id="response">-->
        <div class="form-div">
            <span class="button-3 prev-btn">Back</span>
            <input type="submit" value="Update" id="pic-update-btn" name="pic-update-btn" class="button-2">
        </div>

        <?= $this->Form->end() ?>
    </div>
</div><!-- Update Logo Section End -->


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<script>

    $(document).ready(function () {

        // Webfront Update Logo Code 

        $imageDiv = $('.uploaded-img').croppie({
            enableExif: true,
            viewport: {
                width: 250,
                height: 250,
                type: 'square'
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

        $('.search-btn').on('click', function () {
            $('.text-file').click();
        });

        $('#update-logo-form').validate({
            rules: {
                logo: "required"
            },
            messages: {
                logo: "Please Choose A Photo To Update"
            },
            errorPlacement: function (error, element) {
                error.insertBefore(element.parent().siblings('.form-div'));
            }

        });

    });
</script>