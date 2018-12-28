<style>    
    .profile-pic-form-div { text-align: center;}
    .profile-pic-form-div .form-div {text-align: center;}
    .profile-pic-form-div #file-browser {margin-left: 5vw;}
</style>
<!-- ProfilePicture Tab Start-->
<div id="ProfilePicture" class="tab-pane fade">
    <div class="img-upload-div">
        <div class="uploaded-img">
        </div>
    </div>
    <div class="profile-pic-form-div">
        <?= $this->Form->create(NULL, ['type' => 'file']); ?>
        <?= $this->Form->control('action', ['type' => 'hidden', 'value' => 'ProfilePicture']); ?>
        <input type="file" name="uploaded_image" id="file-browser" style="display: none;">
        <div class="browse-btn" onclick="$('#file-browser').click();">Browse Image</div><br><br>
        <input type="hidden" name="response" id="response">
        <div class="form-div">
            <input type="submit" value="Update" id="updatePicBtn" name="update-btn" class="button-2">
            <a class="button-3" href="<?= HTTP_ROOT; ?>merchants">Cancel </a>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div><!-- ProfilePicture Tab End-->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

<script>
            $(document).ready(function () {

                $imageDiv = $('.uploaded-img').croppie({
                    enableExif: true,
                    viewport: {
                        width: 120,
                        height: 120,
                        type: 'circle'
                    },
                    boundary: {
                        width: 200,
                        height: 200
                    },
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

                $('#updatePicBtn').on('click', function () {
                    $imageDiv.croppie('result', {
                        size: 'viewport'
                    }).then(function (resp) {
                        $('#response').val(resp);
                    });
                });

            });
</script>

