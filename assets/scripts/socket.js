$(function(){
  user_data = new Data_Format();
  $("#submit_btn").on("click",function(){
    //var $btn = $(this).button('loading');
    if( chk_form() ){
      //$btn.button('reset');
      $(".input_data").toggle();
      $(".confirm_data").toggle();
      $(".btn_group").toggle();
      $(this).hide();
      //$("#ConfirmModal").modal("show");
    }else{
      //alert("no");
      //$btn.button('reset');
    }
  });
  $("#back_btn").on("click",function(){
    $(".input_data").toggle();
    $(".confirm_data").toggle();
    $(".btn_group").toggle();
    $("#submit_btn").show();
  });
  $("#send_btn").on("click",function(){
    //$("#back_btn").hide();
    var $btn = $(this).button('loading');
    $.LoadingOverlaySetup({
      color : "rgba(255,255,255,1)"
    });
    $(".regist_form").LoadingOverlay("show", {
      image       : "Preloader_1.gif"
    });
    $(".side_menu").animate({
      left : "-22%"
    });
    // $(".side_menu").LoadingOverlay("show", {
    //   image       : "Preloader_1.gif"
    // });
    setTimeout(function(){
      $(".regist_form").LoadingOverlay("hide");
      $(".side_menu").animate({
        left : "0%"
      });
      //$(".side_menu").LoadingOverlay("hide");
      $btn.button('reset');
      swal({
        title:"Success!",
        text: '完了しました、ホームに戻ります！',
        type:"success"
      }).then(
        function(){
          window.location.href = "?page=home";
          console.log("this is test");
        },
        function(dismiss){
          //window.location.href = "?page=home";
          console.log("this is test");
        }
      );
    },2000);
  });
  $("#student_number").formatter({
    'pattern': '{{9999999999}}'
  });
  $("#phone_number").formatter({
    'pattern': '{{999}}{{9999}}{{9999}}'
  });
  var page_info = getUrlVars(location.search);
  if( page_info["page"] == "regist" || page_info["page"] == "change" ){
    //$("#alert_connecting").slideToggle("normal");
    iziToast.info({
      id : "info_toast",
      title: 'Info',
      message: 'サーバと接続中です。しばらくお待ちください。',
      target : "#alert_area",
      timeout: false,
      progressBar: false,
    });
    setTimeout(function(){
      var host = "ws://localhost:9999/ws";
      var socket = new WebSocket(host);
      if(socket){

         socket.onopen = function(){
           console.log(JSON.stringify(getUrlVars(location.search)));
           ///getUrlVars(location.search)
           //JSON.stringify()
           socket.send(JSON.stringify(getUrlVars(location.search)));
           //socket.send("this is test");
         }

         socket.onmessage = function(msg){
           //showServerResponse(msg.data);
           console.log(msg);
           setTimeout(function(){
             //$("#alert_connecting").hide("slow");
             try {
               res_data = JSON.parse(msg.data);
               if(res_data["status"] == "ready"){
                 //$("#alert_ready").slideToggle("slow");
                 iziToast.success({
                   title: 'Success',
                   message: '準備ができました！ カードをタッチしてください。',
                   target : "#alert_area",
                   timeout: false,
                   progressBar: false,
                 });
               }else if(res_data["status"] == "ok"){
                 console.log(res_data);
                 user_data.idm = res_data["data"]["idm"];
                 user_data.action = res_data["action"];
                 for(key in res_data["data"]){
                   $("#"+key).val(res_data["data"][key]);
                 }
                 //$("#user_name").attr("disabled","");
                 //$("#student_number").attr("disabled","");
                //  $("#user_name").val(res_data["data"]["user_name"]);
                //  $("#student_number").val(res_data["data"]["student_number"]);
                //  $("#alert_ok").slideToggle("slow",function(){
                //    setTimeout(function(){
                //      $("#cancel_btn").slideToggle("slow");
                //      $("#cancel_btn").on("closed.bs.alert",function(){
                //        $("#alert_area").slideToggle("slow",function(){
                //          $(this).remove();
                //        });
                //      });
                //    },500);
                //  });
                 iziToast.success({
                   title: 'Complete',
                   message: 'カードを確認しました！ 情報を登録してください。',
                   target : "#alert_area",
                   timeout: false,
                   progressBar: false,
                 });
                 //$("#alert_connecting").hide("slow");
                 //$("#alert_ready").hide("slow");
               }else if(res_data["status"] == "timeout"){
                 iziToast.error({
                   title: 'Error',
                   message: "タイムアウトです、もう一度最初からお願いします。",
                   target : "#alert_area",
                   timeout: false,
                   progressBar: false,
                 });
                //  $("#alert_timeout").slideToggle("slow",function(){
                //    setTimeout(function(){
                //      $("#cancel_btn").slideToggle("slow");
                //      $("#cancel_btn").on("closed.bs.alert",function(){
                //        $("#alert_area").slideToggle("slow",function(){
                //          $(this).remove();
                //        });
                //      });
                //    },500);
                //  });
               }else if(res_data["status"] == "error"){
                 iziToast.error({
                   title: 'Error',
                   message: "エラーです。もう一度最初からお願いします。",
                   target : "#alert_area",
                   timeout: false,
                   progressBar: false,
                 });
                //  $("#alert_fail").slideToggle("slow",function(){
                //    setTimeout(function(){
                //      $("#cancel_btn").slideToggle("slow");
                //      $("#cancel_btn").on("closed.bs.alert",function(){
                //        $("#alert_area").slideToggle("slow",function(){
                //          $(this).remove();
                //        });
                //      });
                //    },500);
                //  });
               }
             } catch (e) {
               console.log("this is test");
               console.log(e);
               iziToast.error({
                 title: 'Error',
                 message: "エラーです。もう一度最初からお願いします。",
                 target : "#alert_area",
                 timeout: false,
                 progressBar: false,
               });
              //  $("#alert_fail").slideToggle("slow",function(){
              //    setTimeout(function(){
              //      $("#cancel_btn").slideToggle("slow");
              //      $("#cancel_btn").on("closed.bs.alert",function(){
              //        $("#alert_area").slideToggle("slow",function(){
              //          $(this).remove();
              //        });
              //      });
              //    },500);
              //  });
             }
           },500);
         }

         socket.onerror = function(){
           console.log("error");
           iziToast.error({
             title: 'Error',
             message: "エラーです。もう一度最初からお願いします。",
             target : "#alert_area",
             timeout: false,
             progressBar: false,
           });
         }

         socket.onclose = function(){
           //alert("connection closed....");
           //showServerResponse("The connection has been closed.");
         }

       }else{
         //$("#alert_connecting").slideToggle("slow");
         iziToast.error({
           title: 'Error',
           message: "エラーです。もう一度最初からお願いします。",
           target : "#alert_area",
           timeout: false,
           progressBar: false,
         });
         console.log("invalid socket");
       }
    },700);
  }
})

/**
 * URL解析して、クエリ文字列を返す
 * @returns {Array} クエリ文字列
 */
function getUrlVars()
{
    var vars = {}, max = 0, hash = "", array = "";
    var url = window.location.search;

        //?を取り除くため、1から始める。複数のクエリ文字列に対応するため、&で区切る
    hash  = url.slice(1).split('&');
    max = hash.length;
    for (var i = 0; i < max; i++) {
        array = hash[i].split('=');    //keyと値に分割。
        //vars.push(array[0]);    //末尾にクエリ文字列のkeyを挿入。
        vars[array[0]] = array[1];    //先ほど確保したkeyに、値を代入。
    }

    return vars;
}

function chk_form(){
  var array = [];
  if(!$("#user_name").val()) array.push("氏名を入力してください");
  if(!$("#student_number").val()) array.push("学籍番号を入力してください");
  if(!$("#phone_number").val()) array.push("電話番号を入力してください");
  if(!$("#email").val()) array.push("メールアドレスを入力してください");

  if( array.length > 0 ){
    $("#error_area").html("<p>" + array.join("<br>")).show();
    return false;
  }else{
    $("#error_area").html("").hide();
    elem = $("#regist_form input");
    label = $(".confirm_data");
    for(i = 0 ; i < elem.length ; i++){
      user_data.push($(elem[i]).val());
      $(label[i]).text($(elem[i]).val());
    }
    console.log(user_data.createObj());
    return true;
  }
}

Data_Format = function( user_name , student_number , phone_number , email , idm  , action ){
  this.index = 0;
  this.idm = idm;
  this.action = action;
  this.user_name = user_name;
  this.student_number = student_number;
  this.phone_number = phone_number;
  this.email = email;
}

Data_Format.prototype.push = function( data , i ){
  if( i == undefined ){
    switch( this.index ){
      case 0:{
        this.user_name = data;
        break;
      }
      case 1:{
        this.student_number = data;
        break;
      }
      case 2:{
        this.phone_number = data;
        break;
      }
      case 3:{
        this.email = data;
        break;
      }
      default:{
        this.index = 0;
        break;
      }
    }
    this.index = ( this.index + 1 ) % 4;
    return;
  }else if( !isNaN( i ) ){
    switch( i ){
      case 0:{
        this.user_name = data;
        return;
      }
      case 1:{
        this.student_number = data;
        return;
      }
      case 2:{
        this.phone_number = data;
        return;
      }
      case 3:{
        this.email = data;
        return;
      }
      default:{
        return;
      }
    }
  }else{
    return;
  }
}

Data_Format.prototype.createjson = function(){
  return JSON.stringify({
    "action" : this.action , "idm" : this.idm ,
    "user_name" : this.user_name , "student_number" : this.student_number ,
    "phone_number" : this.phone_number , "email" : this.email
  });
}

Data_Format.prototype.createObj = function(){
  return {
    "action" : this.action , "idm" : this.idm ,
    "user_name" : this.user_name , "student_number" : this.student_number ,
    "phone_number" : this.phone_number , "email" : this.email
  };
}
