$(function(){
  $("#submit_btn").on("click",chk_form);
  var page_info = getUrlVars(location.search);
  if( page_info["page"] == "regist" || page_info["page"] == "change" ){
    $("#alert_connecting").slideToggle("normal");
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
                 $("#alert_ready").slideToggle("slow");
               }else if(res_data["status"] == "ok"){
                 console.log(res_data);
                 for(key in res_data["data"]){
                   $("#"+key).val(res_data["data"][key]);
                 }
                //  $("#user_name").val(res_data["data"]["user_name"]);
                //  $("#student_number").val(res_data["data"]["student_number"]);
                 $("#alert_ok").slideToggle("slow",function(){
                   setTimeout(function(){
                     $("#cancel_btn").slideToggle("slow");
                     $("#cancel_btn").on("closed.bs.alert",function(){
                       $("#alert_area").slideToggle("slow",function(){
                         $(this).remove();
                       });
                     });
                   },500);
                 });
                 //$("#alert_connecting").hide("slow");
                 //$("#alert_ready").hide("slow");
                 $("#regist_form").slideToggle("slow");
               }else if(res_data["status"] == "timeout"){
                 $("#alert_timeout").slideToggle("slow",function(){
                   setTimeout(function(){
                     $("#cancel_btn").slideToggle("slow");
                     $("#cancel_btn").on("closed.bs.alert",function(){
                       $("#alert_area").slideToggle("slow",function(){
                         $(this).remove();
                       });
                     });
                   },500);
                 });
               }else if(res_data["status"] == "error"){
                 $("#alert_fail").slideToggle("slow",function(){
                   setTimeout(function(){
                     $("#cancel_btn").slideToggle("slow");
                     $("#cancel_btn").on("closed.bs.alert",function(){
                       $("#alert_area").slideToggle("slow",function(){
                         $(this).remove();
                       });
                     });
                   },500);
                 });
               }
             } catch (e) {
               console.log(e);
               $("#alert_fail").slideToggle("slow",function(){
                 setTimeout(function(){
                   $("#cancel_btn").slideToggle("slow");
                   $("#cancel_btn").on("closed.bs.alert",function(){
                     $("#alert_area").slideToggle("slow",function(){
                       $(this).remove();
                     });
                   });
                 },500);
               });
             }
           },500);
         }

         socket.onerror = function(){
           console.log("error");
           //$("#alert_connecting").hide("slow");
           $("#alert_fail").slideToggle("slow");
         }

         socket.onclose = function(){
           //alert("connection closed....");
           //showServerResponse("The connection has been closed.");
         }

       }else{
         $("#alert_connecting").slideToggle("slow");
         $("#alert_fail").slideToggle("slow");
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
    $("#error_area").html("<p>" + array.join("<br>"));
    return false;
  }else{
    $("#error_area").html("");
    return false;
  }
}
