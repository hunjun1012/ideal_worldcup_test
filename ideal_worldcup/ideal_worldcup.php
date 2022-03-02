<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/Common.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/FrontCommon.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/StringUtil.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/DBUtil.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/FileUtil.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/Logger.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/log/marcketing_config.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/sms/smsProc.php");
$counselGbn = getParamDef("counselGbn", "25746");
?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <?
  require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/meta.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/css.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/script.php");
  ?>
  <!-- 모바일에서 웹화면 들어올 때 모바일 페이지로 리다이렉트 -->
  <script>
    if ($(window).width() < 780) {
      $.getScript('js/nbw-parallax.js');
    }
    if (window.innerWidth < 780) {
      window.location.href = 'https://www.isoohyun.co.kr/nm/html/lovetest/ideal_worldcup.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';
    }
  </script>
  <!-- 모바일에서 웹화면 들어올 때 모바일 페이지로 리다이렉트 -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" type="text/css" href="/new/css/sidemenu.css">
  <script src="https://developers.kakao.com/sdk/js/kakao.js"></script>
  <script>
    //페이지 열릴 때 show(0)으로 이동
    $(document).ready(function() {
      // show(0);
    });
  </script>
  <!-- motion Style -->
  <style>
    #img_m:hover {
      margin-top: 500px;
      margin-left: 300px;
      cursor: pointer;
      animation: fadein 0.5s;
      -moz-animation: fadein 0.5s;
      /* Firefox */
      -webkit-animation: fadein 0.5s;
      /* Safari and Chrome */
      -o-animation: fadein 0.5s;
      /* Opera */
    }

    #img_w:hover {
      margin-top: 500px;
      margin-left: 700px;
      cursor: pointer;
      animation: fadein 0.5s;
      -moz-animation: fadein 0.5s;
      /* Firefox */
      -webkit-animation: fadein 0.5s;
      /* Safari and Chrome */
      -o-animation: fadein 0.5s;
      /* Opera */
    }

    @keyframes fadein {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @-moz-keyframes fadein {

      /* Firefox */
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @-webkit-keyframes fadein {

      /* Safari and Chrome */
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @-o-keyframes fadein {

      /* Opera */
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .join-charge {
      background-color: #c1eaec;
    }
  </style>
</head>

<body>
  <?
  require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/header2.php");
  ?>
  <?
  require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/skyscraper3.php");
  ?>
  <!-- side menu start -->
  <div id="floating_open">
    <h2 class="lnb-tit">러브테스트</h2><br>
    <ul class="main_menu_list">
      <li><a href="/new/fate/fate03.php">이상형찾기</a></li>
      <li class="on"><a href="/new/lovetest/ideal_worldcup.php">나의 이상형 월드컵</a></li>
      <li><a href="/new/lovetest/mbti_test.php">MBTI 이상형 TEST</a></li>
      <li><a href="/new/fate/fate08.php">결혼시기 TEST</a></li>
      <li><a href="/new/fate/fate11.php">결혼체질도 TEST</a></li>
      <li><a href="/new/fate/fate21.php">재혼가능성 TEST</a></li>
      <li><a href="/new/fate/fate15.php">노블레스가입비 TEST</a></li>
      <li><a href="/new/fate/fate26.php">펜트하우스 TEST</a></li>
      <li><a href="/new/fate/fate18.php">내게 맞는 커플매니저</a></li>
    </ul>
  </div>
  <!-- side menu end -->

  <div class="content">
    <div class="bannerwrap">
      <div class="wrap">
        <!-- show(0) -->
        <section id="lovetest">
          <div class="join-charge">
            <div class="" style="background-image: url('/new/image/ideal_worldcup/index_bg.png'); height:600px;  background-repeat: no-repeat; background-position:center;">
              <div>
                <img id="img_m" style="margin-top: 500px; margin-left:300px; cursor:pointer;" src="/new/image/ideal_worldcup/index_btn_m.png" onclick="javascript:location.href='/new/lovetest/ideal_worldcup1.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';">
                <img id="img_w" style="margin-top: 500px; margin-left:700px; cursor:pointer;" src="/new/image/ideal_worldcup/index_btn_w.png" onclick="javascript:location.href='/new/lovetest/ideal_worldcup2.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';">
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
  </div>

  <!-- footer start -->
  <div class="footer">
    <?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/footer.php");
    ?>
    <div style="padding-bottom: 50px; background-color:#222222"></div>
  </div>
  <? include_once($_SERVER["DOCUMENT_ROOT"] . "/new/log/log_common.php"); ?>
  <!-- footer end -->
  </form>
  <iframe src="" id="counselResult" name="counselResult" width="0" height="0" style="display:none;" frameborder="0"></iframe>
</body>

</html>