<!-- Yandex.Metrika counter INFOtoway.ru-->
<script type="text/javascript">(function (d, w, c) {
    (w[c] = w[c] || []).push(function () {
      try {
        w.yaCounter<?=$this->id?> = new Ya.Metrika({
          id: <?=$this->id?>,
          webvisor: true,
          clickmap: true,
          trackLinks: true,
          accurateTrackBounce: true
        });
      } catch (e) {
      }
    });
    var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () {
      n.parentNode.insertBefore(s, n);
    };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
    if (w.opera == "[object Opera]") {
      d.addEventListener("DOMContentLoaded", f, false);
    } else {
      f();
    }
  })(document, window, "yandex_metrika_callbacks");</script>
<!-- /Yandex.Metrika -->

<!-- Yandex.Metrika counter INFOtoway.ru-->
<noscript>
  <div><img src="//mc.yandex.ru/watch/<?=$this->id?>" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- Yandex.Metrika counter -->
