<script type="text/javascript">
    // Custom example logic
    var <?=$config['up_name']?>;
    $(document).ready(function () {
        <?=$config['up_name']?> = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',

            browse_button : '<?=$config['browse_button']?>', // you can pass in id...
            url : "<?=$config['url']?>",
            max_file_count: <?=$config['files_count']?>,
            filters : {
                max_file_size : '<?=$config['max_filesize']?>',

                mime_types: [
                    <?php
                        $mts = array();
                        if($config['mime_types']) foreach($config['mime_types'] as $mime_type)
                            $mts[] = '{title : "'.$mime_type['title'].'", extensions : "'.$mime_type['exts'].'"}';
                        echo implode(',', $mts);
                    ?>
                ]
            },

            // Flash settings
            flash_swf_url : '<?=$publicPath?>/js/Moxie.swf',
            // Silverlight settings
            silverlight_xap_url : '<?=$publicPath?>/js/Moxie.xap',

            init: {

                FilesAdded: function(up, files) {
                    while (  up.files.length > up.settings.max_file_count ) {
                        up.removeFile(up.files[0]);

                    }
                    files= files.slice(-up.settings.max_file_count);
                    plupload.each(files, function(file) {
                        $("#<?=$config['filelist']?>").append('<div id="' + file.id + '" class="plupload-file <?=$config['up_name']?>"><div class="name">' + file.name + ' (' + plupload.formatSize(file.size) + ')</div><div class="progress"><div></div></div><div class="cancel"></div><div class="errors"></div><div class="clearfix"></div></div>');
                    });
                    up.refresh();
                    <?=$config['up_name']?>.start();
                },

                FileUploaded: function(up, file, info) {

                    $("#" + file.id + " .progress").hide();
                    $("#" + file.id + " .cancel").hide();
                    res = $.parseJSON(info.response);
					if(res!=null)
                    if(res['OK']){
                     
					
                        if($("#photolist .photo").size() == up.settings.max_file_count){
							var s  =  $("#photolist .photo").first().find(".close").click();
						}
                            <?=$config['FileUploaded']?>(up, file, info, res);
                    }else {
						 $("#" + file.id).hide();
                        alert("error "+res['error']);
                        /*    for(var err in res['errors'])
                         $("#" + file.id + " .errors").append('<div class="err">' + res['errors'][err] + '</div>');
                         $("#" + file.id + " .errors").show();
                         */
                    }

                },

                UploadProgress: function(up, file) {
                    $("#" + file.id + " .progress div").css("width", file.percent + "%");
                },

                Error: function(up, err) {
                    alert("Error");
                    $("#" + err['file'].id + " .progress").hide();
                    $("#" + err['file'].id + " .cancel").hide();
                    $("#" + err['file'].id + " .errors").html(err.message).show();
                },

                <?php if($config['StateChanged']): ?>StateChanged: function(up) { if(<?=$config['StateChanged']?>) <?=$config['StateChanged']?>(up); },<?php endif; ?>
                <?php if($config['PostInit']): ?>PostInit: function(up) { if(<?=$config['PostInit']?>) <?=$config['PostInit']?>(up);},<?php endif; ?>
            }
        });
        <?=$config['up_name']?>.init();
    });
    $(document).on("click", ".photo .cancel", function () {
        file = $(this).parent();
        <?=$config['up_name']?>.removeFile(file.attr("id"));
        file.hide();
    });
</script>