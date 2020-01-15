(function($){
  var widget_uploader = {
    init: function(browse_el){
      var el_id = 'uploader-'+guid();
      var baseurl = $(browse_el).data('baseurl');
      $(browse_el)
          .attr('id', el_id)
          .removeData('baseurl');

      var uploader = new plupload.Uploader({
        browse_button: el_id,
        url: '/api/uploader',
        filters: {
          mime_types : [
            { title : "Image files", extensions : "jpg,gif,png" },
          ],
          max_file_size: "10mb"
        },
        multipart_params: $(browse_el).data(),
        max_retries: 3,
        resize: {
          width: 800,
          height: 600,
          crop: false,
          quality: 80
        },
        flash_swf_url: baseurl+'/plupload/Moxie.swf',
        silverlight_xap_url: baseurl+'/plupload/Moxie.xap'
      });
      uploader.bind('FilesAdded', function(up, files) {
        uploader.start();
      });
      uploader.bind('Error', function(up, error) {
        alert(error.message);
      });
      uploader.bind('FileUploaded', function(up, files, res) {
        var res = res.response;
        res = JSON.parse(res);
        var filename = res.filename;

        var el = up.settings.browse_button;
        $(el)
          .html('')
          .append($('<img>').attr('src', '/img/thumb/exacly/174x146/'+filename))
          .append($('<input id="user-avatar" name="avatar" type="hidden">').val(filename));
      });
      uploader.init();
    }
  }

  $(document).ready(function(){
    $('a.uploader').each(function(){
      widget_uploader.init(this);
    });
  });
})(jQuery);
