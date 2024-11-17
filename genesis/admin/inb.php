<div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="mmailbox.php"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right"><?php echo $messagesrow['count'];?></span></a></li>
                <li><a href="sent.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="mtrash.php"><i class="fa fa-trash-o"></i> Trash </a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>