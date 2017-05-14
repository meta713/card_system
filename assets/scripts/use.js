$(function(){
  $("[btn]").on("click",function(){
    if ( !$(this).find("a").attr("disabled") ){
      var text = $(this).find("span").text();
      var attr = $(this).attr("btn");
      $(this).find("span").text($("[btn]:first").find("span").text());
      $(this).attr("btn",$("[btn]:first").attr("btn"));
      $("[btn]:first").attr("btn",attr);
      $("[btn]:first").find("span").attr("btn");
      $("[btn]:first").find("span").text(text);
      $("[btn]").find("a").attr("disabled","true");
      $("[btn]").animate({
        "margin-left" : "0px",
        "width" : "33.33333333%",
        "padding" : "14px"
      });
      $("[btn] > a").animate({
        "border-radius" : "50px"
      });
      $("[btn]:first").animate({
        "margin-left" : "0px",
        "width" : "100%",
        "padding" : "40px"
      },{
        "complete" : function(){
          connect_socket_use();
        }
      });
      // $("[btn]:first").find("a").animate({
      //   "border-radius" : "6px"
      // });
      //console.log($("[btn]")[0]);
    }
  });
})

function connect_socket_use(){
  var page_info = getUrlVars(location.search);
  if( page_info["page"] == "use" ){
    iziToast.info({
      id : "info_toast",
      title: 'Info',
      message: 'サーバと接続中です。しばらくお待ちください。',
      position: 'bottomRight',
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
           socket.send(JSON.stringify(page_info));
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
                   position: 'bottomRight',
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
                   position: 'bottomRight',
                   timeout: false,
                   progressBar: false,
                 });
                 //$("#alert_connecting").hide("slow");
                 //$("#alert_ready").hide("slow");
               }else if(res_data["status"] == "timeout"){
                 iziToast.error({
                   title: 'Error',
                   message: "タイムアウトです、もう一度最初からお願いします。",
                   position: 'bottomRight',
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
                   position: 'bottomRight',
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
                 position: 'bottomRight',
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
             position: 'bottomRight',
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
           position: 'bottomRight',
           timeout: false,
           progressBar: false,
         });
         console.log("invalid socket");
       }
    },700);
  }
}
