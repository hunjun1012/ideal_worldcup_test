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
      window.location.href = 'https://www.isoohyun.co.kr/new/lovetest/mbti_test.php?counselGbn=<?= $counselGbn ?>&mctkey=<?= $mctkey ?>';
    }
  </script>
  <script src="https://developers.kakao.com/sdk/js/kakao.js"></script>
  <script>
    Kakao.init('16b3c92425889edb797d2dc78b3d1428'); // kakao javascript키
    //카카오 정보 Get
    function kakaoGetData(ideal_result) { //이상형 결과값 인자로 받음
      Kakao.Auth.login({
        success: function(response) {
          console.log(response);
          Kakao.API.request({
            url: '/v2/user/me',
            success: function(response) {
              var user_id = "k_" + response.id; // 아이디
              var birthyear = response.kakao_account.birthyear; // 생일
              var email = response.kakao_account.email; // 이메일
              var gender = response.kakao_account.gender; // 성별
              if (gender == 'male') { // DB에 맞는 성별처리
                gender = '1';
              } else {
                gender = '2';
              }
              var phone_number = response.kakao_account.phone_number; // 핸드폰번호
              var phone_number = phone_number.replace('+82 ', '0'); // 핸드폰 앞자리 치환
              var nickname = response.properties.nickname; // 카카오톡 닉네임
              $('#user_id').val(user_id);
              $('#birthday').val(birthyear);
              $('#email').val(email);
              $('#gender').val(gender);
              $('#phone').val(phone_number);
              $('#name').val(nickname);
              // 이상형 결과
              $('#ideal_result').val(ideal_result);
            },
            fail: function(error) {
              console.log(error)
            },
          })
          Kakao.API.request({
            url: '/v1/user/shipping_address',
            success: function(response) { // 첫번째 등록한 주소
              var base_address = response.shipping_addresses[0].base_address;
              var detail_address = response.shipping_addresses[0].detail_address;
              var zone_number = response.shipping_addresses[0].zone_number;
              $('#area').val(base_address); //지역
              $('input[name="area_post_number"]').val(zone_number); // 우편번호
            },
            fail: function(error) {
              console.log(error)
            },
          })
          // Settimeout 후 카카오 정보 및 form 전송
          setTimeout(function() {
            $('#frm').validate({
              success: function() {
                this.target = "counselResult";
                this.action = "/new/common/ideal_worldcup_proc.php"; // 1초 후 값을 담아서 proc파일로 전송 후 db insert
                this.submit();
                var name = $('input[name=name]').val();
                var email = $('input[name=email1]').val() + '@' + $('input[name=email2]').val();
                $('#resultName').html($('input[name=name]').val());
                $('#resultEmail').html($('input[name=email]').val());
                $("#resultPhone").html($('input[name=phone]').val());
              }
            });
          }, 1000);
        },
        fail: function(error) {
          console.log(error)
        },
      })
    }

    // 이미지 src 주소 배열처리
    var select_images = [];
    var select_images2 = [];
    // 이미지 결과값 배열처리
    var image_result = [];
    var image_result2 = [];
    // show(8) result & show(9) result
    var result08;
    var result09;
    // 최종 이상형 결과값 변수
    var ideal_result;

    //ready section show(0) start
    $(document).ready(function() {
      show(0);
    });

    function show(idx, src, result) { //onclick event 처리 함수 및 데이터 처리
      if (idx == 1) { // 출생년도 및 학력 유효성 검사
        if ($('#school').val() == "") {
          alert("학력을 선택해주세요");
          return false;
        } else if ($('select[name=new_birthday]').val() == "") {
          alert('출생년도를 선택해주세요.');
          $('select[name=new_birthday]').focus();
          return;
        }
      } else if (idx == 2) { // 8강 start
        select_images[0] = src;
        image_result[0] = result;
        // console.log(select_images);
        // console.log(image_result);
      } else if (idx == 3) {
        select_images[1] = src;
        image_result[1] = result;
        // console.log(select_images);
        // console.log(image_result);
      } else if (idx == 4) {
        select_images[2] = src;
        image_result[2] = result;
        // console.log(select_images);
        // console.log(image_result);
      } else if (idx == 5) { // 4강 start & 이 구간부터 src 선택 값으로 change 및 result change
        select_images[3] = src;
        image_result[3] = result;
        // console.log(select_images);
        // console.log(image_result);
        $('input[name=ideal_text11]').attr('value', image_result[0]);
        $('input[name=ideal_text12]').attr('value', image_result[1]);
        $("#select_image01").attr("src", select_images[0]);
        $("#select_image02").attr("src", select_images[1]);
      } else if (idx == 6) {
        select_images2[0] = src;
        image_result2[0] = result;
        // console.log(select_images2);
        // console.log(image_result);
        $('input[name=ideal_text13]').attr('value', image_result[2]);
        $('input[name=ideal_text14]').attr('value', image_result[3]);
        $("#select_image03").attr("src", select_images[2]);
        $("#select_image04").attr("src", select_images[3]);
      } else if (idx == 7) { // 결승 start  & 이 구간부터 src 및 result 최종 값 
        select_images2[1] = src;
        image_result2[1] = result;
        // console.log(select_images2);
        // console.log(image_result);
        $('input[name=ideal_text15]').attr('value', image_result2[0]);
        $('input[name=ideal_text16]').attr('value', image_result2[1]);
        $("#select_image_result1").attr("src", select_images2[0]);
        $("#select_image_result2").attr("src", select_images2[1]);
      } else if (idx == 8) { // result08에 최종 select 값 담아서 show(9) section에 ideal_result 최종 변수에 넘김 & success함수로 onclick
        result08 = result;
        // console.log("result08 : " + result08);
      } else if (idx == 9) { // 결과 확인 중 gif section에서 ideal_result 판단 후 result section show start
        result09 = result08;
        // console.log("result09 : " + result09);
        setTimeout(() => {
          success();
        }, 2000);
      } else if (idx == 10) { //show(10)에서 최종결과
        ideal_result = result09;
        // console.log("ideal_result : " + ideal_result);
      }

      // 이상형 결과에 따라서 페이지 section 처리
      if (ideal_result == "청순한 여자" || ideal_result == "강아지상 여자" || ideal_result == "긴머리 여자") {
        this.ideal_result = "청순&강아지상&긴머리 여자";
        $('section').hide();
        $('.ideal_result01').show();
      } else if (ideal_result == "섹시한 여자" || ideal_result == "고양이상 여자" || ideal_result == "단발 여자") {
        this.ideal_result = "섹시&고양이상&단발 여자";
        $('section').hide();
        $('.ideal_result02').show();
      } else if (ideal_result == "고연봉 여자") {
        this.ideal_result = "고연봉 여자";
        $('section').hide();
        $('.ideal_result04').show();
      } else if (ideal_result == "고학력 여자") {
        this.ideal_result = "고학력 여자";
        $('section').hide();
        $('.ideal_result03').show();
      } else {
        $('section').hide();
        $('section:eq(' + idx + ')').show();
      }

    }

    //함수 한번만 실행 변수
    var is_action = false;

    function success() {
      show(10);
      if (is_action === true) { // 카카오 버튼 눌렀을 때 ideal_result변수를 kakaogetdata로 인자값 전송 (한번만 실행)
        return false;
      }
      is_action = true;
      // kakaodata함수로 이상형 결과 값 담아서 날려줌
      kakaoGetData(ideal_result);
    }

    // 학력 및 출생년도 select color change
    function changecolor1() {
      $(".new_birthday").css("background-color", "#e3af63");
      $(".new_birthday").css("border", "3px solid #e3af63");
      $(".new_birthday").css("color", "white");
    }

    function changecolor2() {
      $(".school").css("background-color", "#e3af63");
      $(".school").css("border", "3px solid #e3af63");
      $(".school").css("color", "white");
    }
  </script>
  <!-- p text -->
  <style>
    .p_text {
      text-align: center;
      font-size: 20px;
      padding-top: 20px;
      padding-bottom: 20px;
      color: #e3af63;
    }

    .list_box {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 10%;
      font-size: 15px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.5;
      margin-bottom: 30px;
    }

    .list_box:hover {
      width: 80%;
      border: 3px solid #6645b8;
      text-align: center;
      padding: 10%;
      font-size: 15px;
      color: white;
      background-color: #7555c4;
      opacity: 0.5;
      margin-bottom: 30px;
    }

    .join-charge {
      background-color: white;
    }

    input[id="gender1"]+label {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.5;
      margin-bottom: 30px;
    }

    input[id="gender1"]:checked+label {
      width: 80%;
      border: 3px solid #6645b8;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: white;
      background-color: #7555c4;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    input[id="gender2"]+label {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    input[id="gender2"]:checked+label {
      width: 80%;
      border: 3px solid #6645b8;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: white;
      background-color: #7555c4;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    input[id="marry1"]+label {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    input[id="marry1"]:checked+label {
      width: 80%;
      border: 3px solid #dda85b;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: white;
      background-color: #e3af63;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    input[id="marry2"]+label {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    input[id="marry2"]:checked+label {
      width: 80%;
      border: 3px solid #dda85b;
      text-align: center;
      padding: 7% 14%;
      font-size: 20px;
      color: white;
      background-color: #e3af63;
      opacity: 0.7;
      margin-bottom: 30px;
    }

    #new_birthday {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 10%;
      font-size: 20px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.5;
      margin-bottom: 30px;
    }


    #school {
      width: 80%;
      border: 3px solid white;
      text-align: center;
      padding: 10%;
      font-size: 20px;
      color: black;
      background-color: whitesmoke;
      opacity: 0.5;
      margin-bottom: 30px;
    }

    select {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      text-indent: 15px;
    }

    /* 나의 이상형 월드컵 텍스트 배치 */
    .worldcup_text {
      font-size: 15px;
      font-weight: bold;
      color: white;
      background-color: #e3af63;
      border-radius: 50px 50px 50px 50px;
      padding: 10px 60px;
      height: 30px;
      line-height: 30px;
      position: absolute;
      top: 250px;
      left: 50px;
      text-align: center;
      margin: auto;
      z-index: 10;
    }

    /* 이상형 월드컵 상단 이미지 */
    .worldcup_img {
      zoom: 0.7;
      position: absolute;
      top: 10px;
      left: 83%;
      z-index: 10;
    }

    /* 나의 이상형 __여자1 */
    .ideal_text1 {
      border: 2px solid #dda85b;
      font-size: 15px;
      font-weight: bold;
      color: white;
      background-color: #e3af63;
      height: 30px;
      line-height: 30px;
      position: absolute;
      top: 200px;
      left: 80px;
      text-align: center;
      margin: auto;
      z-index: 10;
      width: 60%;
    }

    /* 나의 이상형 __여자2 */
    .ideal_text2 {
      border: 2px solid #dda85b;
      font-size: 15px;
      font-weight: bold;
      color: white;
      background-color: #e3af63;
      height: 30px;
      line-height: 30px;
      position: absolute;
      top: 500px;
      left: 80px;
      text-align: center;
      margin: auto;
      z-index: 10;
      width: 60%;
    }

    .input_text {
      border: none;
      background: none;
      text-align: center;
      color: #fff;
      font-size: 20px;
      font-weight: bold;
      width: 60%;
      opacity: 1.0;
    }

    .input_text:disabled {
      border: none;
      background: none;
      text-align: center;
      color: #fff;
      font-size: 20px;
      font-weight: bold;
      width: 60%;
      opacity: 1.0;
    }
  </style>
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


    <!-- show(0) -->
    <section id="lovetest">
      <form id="frm" name="frm" method="post">
        <input type="hidden" name="counselGbn" value="<?= getParam("counselGbn", "25746") ?>" />
        <input type="hidden" name="counselGbn2" value="나의 이상형 월드컵_M" />
        <input type="hidden" name="marriage" value="10501" />
        <input type="hidden" name="ideal_result" id="ideal_result" value="" />
        <input type="hidden" id="name" name="name">
        <input type="hidden" id="gender" name="gender">
        <input type="hidden" id="birthday" name="birthday">
        <input type="hidden" id="area" name="area">
        <input type="hidden" id="area_post_number" name="area_post_number">
        <input type="hidden" id="phone" name="phone">
        <input type="hidden" id="email" name="email">
        <input type="hidden" name="content" />
        <input type="hidden" name="user_id" id="user_id" />
        <div class="join-charge">
          <div style="background-image: url('../../static/images/lovetest/ideal_worldcup/mobile_bg01.png'); height:550px; background-repeat: no-repeat; background-position:center;">
            <div class="input-box">
              <div>
                <img style="padding-top: 10px; zoom:0.7; float:right; margin-right:18px;" src="../../static/images/lovetest/ideal_worldcup/m_p_q_top_img.png" alt="" />
              </div><br><br><br><br>
              <p class="p_text">나의 정보 입력하고 시작하기</p><br><br>
              <div style="text-align: center; margin-top:50px;">
                <div style="padding-top: 7px;">
                  <input id="marry1" type="radio" name="marriage" value="10501" style="display: none;" /><label for="marry1"> 초혼</label>&nbsp;&nbsp;
                  <input id="marry2" type="radio" name="marriage" value="10502" style="display: none;" /> <label for="marry2">재혼</label>
                </div><br><br>
                <div style="padding-bottom: 10px; ">
                  <select onchange="changecolor1();" id="new_birthday" name="new_birthday" class="new_birthday" style="height:50px;">
                    <option value="">출생년도</option>
                    <? for ($i = 1950; $i < date('Y'); $i++) { ?>
                      <option value="<?= $i ?>"><?= $i; ?>년</option>
                    <? } ?>
                  </select>
                </div>
                <div style="padding-bottom: 10px; ">
                  <select onchange="changecolor2();" id="school" name="school" class="school" message="학력을 선택해주세요." style="height:50px;">
                    <option value="">학력</option>
                    <option value="대학(2, 3년제) 재학">대학(2, 3년제) 재학</option>
                    <option value="대학(2, 3년제) 졸업">대학(2, 3년제) 졸업</option>
                    <option value="대학(4년제) 재학">대학(4년제) 재학</option>
                    <option value="대학(4년제) 졸업">대학(4년제) 졸업</option>
                    <option value="대학원(석사) 재학">대학원(석사) 재학</option>
                    <option value="대학원(석사) 졸업">대학원(석사) 졸업</option>
                    <option value="대학원(박사) 재학">대학원(박사) 재학</option>
                    <option value="대학원(박사) 졸업">대학원(박사) 졸업</option>
                    <option value="고등학교 졸업">고등학교 졸업</option>
                    <option value="기타">기타</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- 이전페이지, 다음페이지 -->
            <center>
              <button style="font-size:20px; width: 80%; height:50px; border:none; cursor:pointer; background-color:#e3af63; color:white;" type="button" onclick="show(1);return false;">다음페이지→</button>
            </center>
            <div style="margin-left:20px; display:block; margin-top:-480px; cursor:pointer;">
              <img style="zoom: 0.7;" src="../../static/images/lovetest/ideal_worldcup/m_btn_prev.png" alt="" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup.php';" />
            </div>
            <!-- 이전페이지, 다음페이지 -->
          </div>
        </div>
      </form>
      <iframe src="" id="counselResult" name="counselResult" width="0" height="0" style="display:none;" frameborder="0"></iframe>
    </section>

    <!-- show(1) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="../../static/images/lovetest/ideal_worldcup/m_p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 8강 1/4</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img class="image_size" onclick="show(2,'../../static/images/lovetest/ideal_worldcup/1.jpg','청순한 여자');" src="../../static/images/lovetest/ideal_worldcup/1.jpg">
          <img class="image_size" onclick="show(2,'../../static/images/lovetest/ideal_worldcup/2.jpg','섹시한 여자');" src="../../static/images/lovetest/ideal_worldcup/2.jpg">
        </div>
        <div class="ideal_text1">
          <input value="청순한 여자" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input value="섹시한 여자" class="input_text" disabled>
        </div>
    </section>

    <!-- show(2) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="/new/image/ideal_worldcup/p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 8강 2/4</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img class="image_size" onclick="show(3,'../../static/images/lovetest/ideal_worldcup/3.jpg','강아지상 여자');" src="../../static/images/lovetest/ideal_worldcup/3.jpg">
          <img class="image_size" onclick="show(3,'../../static/images/lovetest/ideal_worldcup/4.jpg','고양이상 여자');" src="../../static/images/lovetest/ideal_worldcup/4.jpg">
        </div>
        <div class="ideal_text1">
          <input value="강아지상 여자" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input value="고양이상 여자" class="input_text" disabled>
        </div>
      </div>
    </section>

    <!-- show(3) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="/new/image/ideal_worldcup/p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 8강 3/4</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img class="image_size" onclick="show(4,'../../static/images/lovetest/ideal_worldcup/5.jpg','단발 여자');" src="../../static/images/lovetest/ideal_worldcup/5.jpg">
          <img class="image_size" onclick="show(4,'../../static/images/lovetest/ideal_worldcup/6.jpg','긴머리 여자');" src="../../static/images/lovetest/ideal_worldcup/6.jpg">
        </div>
        <div class="ideal_text1">
          <input value="단발 여자" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input value="긴머리 여자" class="input_text" disabled>
        </div>
      </div>
    </section>

    <!-- show(4) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="/new/image/ideal_worldcup/p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 8강 4/4</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img class="image_size" onclick="show(5,'../../static/images/lovetest/ideal_worldcup/7.jpg','고연봉 여자');" src="../../static/images/lovetest/ideal_worldcup/7.jpg">
          <img class="image_size" onclick="show(5,'../../static/images/lovetest/ideal_worldcup/8.jpg','고학력 여자');" src="../../static/images/lovetest/ideal_worldcup/8.jpg">
        </div>
        <div class="ideal_text1">
          <input value="고연봉 여자" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input value="고학력 여자" class="input_text" disabled>
        </div>
      </div>
    </section>

    <!-- show(5) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="/new/image/ideal_worldcup/p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 4강 1/2</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img id="select_image01" class="image_size" onclick="show(6, select_images[0] , image_result[0]);">
          <img id="select_image02" class="image_size" onclick="show(6, select_images[1], image_result[1]);">
        </div>
        <div class="ideal_text1">
          <input name="ideal_text11" value="" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input name="ideal_text12" value="" class="input_text" disabled>
        </div>
      </div>
    </section>

    <!-- show(6) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="/new/image/ideal_worldcup/p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 4강 2/2</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img id="select_image03" class="image_size" onclick="show(7, select_images[2], image_result[2]);">
          <img id="select_image04" class="image_size" onclick="show(7, select_images[3], image_result[3]);">
        </div>
        <div class="ideal_text1">
          <input name="ideal_text13" value="" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input name="ideal_text14" value="" class="input_text" disabled>
        </div>
      </div>
    </section>

    <!-- show(7) -->
    <section id="lovetest">
      <div class="join-charge">
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div class="worldcup_img">
          <img src="/new/image/ideal_worldcup/p_q_top_img.png" alt="" />
        </div>
        <div class="worldcup_text">
          <b>나의 이상형 월드컵 결승</b>
        </div>
        <!-- 이상형월드컵 이미지 및 텍스트 배치 -->
        <div style="height:550px;">
          <img id="select_image_result1" class="image_size" onclick="show(8, select_images2[0] , image_result2[0]);">
          <img id="select_image_result2" class="image_size" onclick="show(8, select_images2[1] , image_result2[1]);">
        </div>
        <div class="ideal_text1">
          <input name="ideal_text15" value="" class="input_text" disabled>
        </div>
        <div class="ideal_text2">
          <input name="ideal_text16" value="" class="input_text" disabled>
        </div>
      </div>
    </section>

    <!-- show(8) 카카오로 결과 확인하기 -->
    <section id="lovetest">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/mobile_bg02_w.png'); height:550px; background-repeat: no-repeat; background-position:center;">

          <div>
            <img style="padding-top: 10px; zoom:0.7; float:right; margin-right:18px;" src="../../static/images/lovetest/ideal_worldcup/m_p_q_top_img.png" alt="" />
          </div>

          <div>
            <img style="width:80%; margin-top:400px; margin-left:40px; cursor:pointer;" src="../../static/images/lovetest/ideal_worldcup/btn_kakao.png" alt="" onclick="javascript:show(9);" />
          </div>
          <!-- 다시하기 -->
          <div style="margin-left:10px; display:block; margin-top:-500px; cursor:pointer;">
            <img src="/new/image/ideal_worldcup/btn_re.png" alt="" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup.php';" />
          </div>
          <!-- 다시하기 -->
        </div>
      </div>
      <iframe src="" id="counselResult" name="counselResult" width="0" height="0" style="display:none;" frameborder="0"></iframe>
    </section>

    <!-- show(9) result 결과를 확인 중입니다. -->
    <section id="lovetest">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/loading_bg.png'); height:550px; background-repeat: no-repeat; background-position:center;">

          <img src="/new/image/ideal_worldcup/loading_gif.gif" style="text-align: center; margin-left:10px; margin-top:70px;">
        </div>
    </section>

    <!-- show(10) result 처리 -->
    <section id="lovetest">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/loading_bg.png'); height:550px; background-repeat: no-repeat; background-position:center;">

          <img src="/new/image/ideal_worldcup/loading_gif.gif" style="text-align: center; margin-left:10px; margin-top:70px;">
        </div>
    </section>

    <!-- show(11) result=1 청순&강아지상&긴머리 여자 -->
    <section id="lovetest" class="ideal_result01">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/m_result.png'); height:550px;">
          <div>
            <img class="image_size" src="/new/image/ideal_worldcup/11.jpg">
            <img style="margin-top:-500px;" src="/new/image/ideal_worldcup/btn_re.png" alt="" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup.php';" />
          </div>
          <div style="display:block; margin-left:70px; margin-top:60px;"><br><br>
            <b style="font-size: 20px;">청순&강아지상&긴머리 여자</b>
          </div>
          <img style="zoom: 0.5; margin-top:130px; margin-left:120px;" src="../../static/images/lovetest/ideal_worldcup/m_result_btn.png" alt="" onclick="window.open('/under/under61m.php?counselGbn=27645');" />
        </div>
      </div>
    </section>
    <!-- show(12) result=2 섹시&고양이상&단발 여자 -->
    <section id="lovetest" class="ideal_result02">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/m_result.png'); height:550px;">
          <div>
            <img class="image_size" src="/new/image/ideal_worldcup/12.jpg">
            <img style="margin-top:-500px;" src="/new/image/ideal_worldcup/btn_re.png" alt="" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup.php';" />
          </div>
          <div style="display:block; margin-left:70px; margin-top:60px;"><br><br>
            <b style="font-size: 20px;">섹시&고양이상&단발 여자</b>
          </div>
          <img style="zoom: 0.5; margin-top:130px; margin-left:120px;" src="../../static/images/lovetest/ideal_worldcup/m_result_btn.png" alt="" onclick="window.open('/under/under61m.php?counselGbn=27645');" />
        </div>
      </div>
    </section>
    <!-- show(13) result=3 고학력 여자 -->
    <section id="lovetest" class="ideal_result03">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/m_result.png'); height:550px;">
          <div>
            <img class="image_size" src="/new/image/ideal_worldcup/13.jpg">
            <img style="margin-top:-500px;" src="/new/image/ideal_worldcup/btn_re.png" alt="" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup.php';" />
          </div>
          <div style="display:block; margin-left:150px; margin-top:60px;"><br><br>
            <b style="font-size: 20px;">고학력 여자</b>
          </div>
          <img style="zoom: 0.5; margin-top:130px; margin-left:120px;" src="../../static/images/lovetest/ideal_worldcup/m_result_btn.png" alt="" onclick="window.open('/under/under61m.php?counselGbn=27645');" />
        </div>
      </div>
    </section>
    <!-- show(14) result=4 고연봉 여자 -->
    <section id="lovetest" class="ideal_result04">
      <div class="join-charge">
        <div class="" style="background-image: url('../../static/images/lovetest/ideal_worldcup/m_result.png'); height:550px;">
          <div>
            <img class="image_size" src="/new/image/ideal_worldcup/14.jpg">
            <img style="margin-top:-500px;" src="/new/image/ideal_worldcup/btn_re.png" alt="" onclick="javascript:location.href='/nm/html/lovetest/ideal_worldcup.php';" />
          </div>
          <div style="display:block; margin-left:150px; margin-top:60px;"><br><br>
            <b style="font-size: 20px;">고연봉 여자</b>
          </div>
          <img style="zoom: 0.5; margin-top:130px; margin-left:120px;" src="../../static/images/lovetest/ideal_worldcup/m_result_btn.png" alt="" onclick="window.open('/under/under61m.php?counselGbn=27645');" />
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