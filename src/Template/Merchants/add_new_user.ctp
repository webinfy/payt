<div class="main-content" style="background: #CCC;">
    <div class="main-content-inner">
        <div class="page-content">
            <!-- /.page-header -->
            <div class="row">
                <div class="page-header" style="margin-left: 0px;border-bottom: none;">
                    <h1 style="margin-top: 8px; margin-left: 15px;">
                        Add New User                         
                    </h1>                    
                </div>
                <div class="col-xs-12">
                    <div class="content-main-area" style="padding: 20px;">
                        <!-- PAGE CONTENT BEGINS -->
                        <?= $this->Form->create(NULL, ['id' => 'userValidation', 'class' => 'form-horizontal']); ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">&nbsp;</label>
                            <div class="col-sm-9">
                                <p style="color: red; font-weight: bold;">All (*) fields are mandatory</p> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Name <span class="required">*</span> : </label>
                            <div class="col-sm-9">
                                <?= $this->Form->control('name', ['placeholder' => 'Name', 'class' => 'col-xs-10 col-sm-5', 'label' => FALSE]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email <span class="required">*</span> : </label>
                            <div class="col-sm-9">
                                <?= $this->Form->control('email', ['placeholder' => 'Email', 'class' => 'col-xs-10 col-sm-5', 'label' => FALSE]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password <span class="required">*</span> : </label>                              
                            <div class="col-sm-9">
                                <?= $this->Form->control('password', ['type' => 'password', 'placeholder' => 'Password', 'class' => 'col-xs-10 col-sm-5', 'label' => FALSE]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Confirm Password <span class="required">*</span> : </label>                             
                            <div class="col-sm-9">
                                <?= $this->Form->control('confirm_password', ['type' => 'password', 'placeholder' => 'Confirm Password', 'class' => 'col-xs-10 col-sm-5', 'label' => FALSE]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Phone No. <span class="required">*</span> : </label>
                            <div class="col-sm-9">
                                <?= $this->Form->control('phone', ['type' => 'text', 'placeholder' => 'Phone No.', 'class' => 'col-xs-10 col-sm-5', 'label' => FALSE]); ?>
                            </div>
                        </div>

                        <div class="form-group">   
                            <div class="col-sm-9 col-md-offset-3">
                                <label class="radio-inline">                                
                                    <input type="radio" name="access" value="2" ng-model="user.access"> Full Access
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="access" value="3" ng-model="user.access" ng-checked="true"> View Only
                                </label>  
                            </div>                               
                        </div>

                        <div class="form-group">  
                            <div class="col-md-offset-3 col-md-9"> 
                                <button class="btn btn-info" type="submit"><i class="ace-icon fa fa-check bigger-110"></i> Create </button>  &nbsp; &nbsp; &nbsp;
                                <a class="btn" href="merchants"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </a>
                            </div>
                        </div>
                        <?= $this->Form->end(); ?>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div><!-- /.row-content -->
    </div>
</div><!-- /.main-content -->

<style>
    label.error {
        color: red;
        margin-left: 10px;
    }
</style>
<script src="<?= HTTP_ROOT ?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?= HTTP_ROOT ?>validation/dist/jquery.validate.min.js" language="javascript"></script>
<script>
    $(function () {
        $("#userValidation").validate({
            ignore: [],
            rules: {
                name: "required",
                phone: "required",
                password:"required",
                confirm_password: {
                  required : true,
                  equalTo : "#password",
              },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: "Enter you name!!",
                phone: "Please enter phone!!",
                email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                },
                password: "Please enter a new password!!",
                confirm_password: {
                  required : "Please enter a confirm password!!",
                  equalTo : "Passsword & confirm password are not matching!!"
              },
            },
            submitHandler: function (form) {
                return true;
            }

        });
    });

</script>