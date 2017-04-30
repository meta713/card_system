//var id_timeout = "";
function inputcheck(){
  // console.log("gaj");
  // make_alert("this is test",$(".panel-body"));
  var array = [];

  if(!$("#login-username").val()) array.push("usernameがありません");
  if(!$("#login-password").val()) array.push("passwordがありません");

  if(array.length > 0){
    make_alert( "データを入力してください" , $(".panel-body") , true);
    return false;
  }else{
    $("#btn-login").attr("disabled","");
    $.ajax({
      type:"POST",
      url:"controller.php",
      data:{username:$("#login-username").val(),password:$("#login-password").val()},
      success:function( data ){
        try{
          j_data = JSON.parse( data );
          if( j_data["status"] == "ok" ){
            console.log("ok");
            location.href = j_data["url"];
          }else{
            make_alert( "ユーザ名、またはパスワードが違います" , $(".panel-body") , true);
          }
          $("#btn-login").removeAttr("disabled");
        }catch(e){
          make_alert( "Server Error status 1" , $(".panel-body") , true);
          $("#btn-login").removeAttr("disabled");
        }
      },
      error:function(){
        make_alert( "Server Error status 2" , $(".panel-body") , true);
        $("#btn-login").removeAttr("disabled");
      }
    });
  }
}

function make_alert( text="", dom , is_error=false ){
  if( text == "" ) return;
  dom.find("[role='alert']").remove();
  //if( id_timeout != "" ) return;
  if( is_error ){
    dom.prepend('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
  '<strong>警告!</strong> '+ text +'</div>');
    dom.find("[role='alert']").show("normal");
  }else{
    dom.prepend('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
  '<strong>成功!</strong> '+ text +'</div>');
    dom.find("[role='alert']").show("normal");
  }
  // id_timeout = setTimeout(function(){
  //   dom.find("[role='alert']").hide("normal",function(){ $(this).remove(); });
  //   id_timeout = "";
  // },3000);
}
