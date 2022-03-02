<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/Common.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/StringUtil.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/DBUtil.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/FileUtil.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/common/Logger.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/new/sms/smsProc.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/nm/common/FrontCommon.php");
$counselGbn = getParam("counselGbn", "25747");
?>
<!DOCTYPE html>
<html class="no-js" lang="ko">

<head>
  <?
  require_once($_SERVER["DOCUMENT_ROOT"] . "/nm/common/meta_k.php");
  ?>
  <!-- 웹으로 모바일 페이지 보면 리다이렉트 -->
  <script>
    if ($(window).width() > 780) {
      $.getScript('js/nbw-parallax.js');
    }
    if (window.innerWidth > 780) {
      window.location.href = 'https://www.isoohyun.co.kr/new/lovetest/ideal_worldcup.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';
    }
  </script>
  <script src="https://developers.kakao.com/sdk/js/kakao.js"></script>
  <script>
    //페이지 열릴 때 show(0)으로 이동
    $(document).ready(function() {
      show(0);
      // $('.isfp').show();
    });
  </script>

</head>

<body>
  <div class="wrap">
    <?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/nm/common/header.php");
    ?>
    <div id="container">
      <?
      require_once($_SERVER["DOCUMENT_ROOT"] . "/nm/common/pageTitle.php");
      ?>
    </div>
    <div id="layer_fixeds" class="phone-links">
      <a class="" href="/nm/common/Counseling.php"><i class=""></i>1:1문의</a>
      <a class="" href="/nm/common/brochure.php"><i class=""></i>브로셔신청</a>
      <a href="tel:025404000"><i class="ico-phone"></i>전화상담</a>
    </div>
    <!-- 시작부분 show(0) -->
    <section id="lovetest">
      <div class="join-charge">
        <div style="position:relative; background-image: url('../../static/images/lovetest/ideal_worldcup/m_start2.png');height: 600px; background-repeat : no-repeat; background-size : cover;background-position:center;">
          <div style="zoom: 0.5;">
            <img style="left: 20px; top:85%;position:absolute;" src="../../static/images/lovetest/ideal_worldcup/m_start_btn01.png" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup1.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';">
            <img style="right: 20px;top:85%; position:absolute;" src="../../static/images/lovetest/ideal_worldcup/m_start_btn02.png" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup2.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';">
          </div>
        </div>
      </div>
    </section>


  </div>
  <!-- //컨텐츠 영역 -->
  <?
  require_once($_SERVER["DOCUMENT_ROOT"] . "/nm/common/footer.php");
  ?>
</body>

</html>