
var check_func = {
	email : function (value) {
		var $notice = $("input#mail + span");
		if (!value) {
			$notice.addClass("notice alert").text("请填写您的邮箱");
			return false;
		}
		var mailReg = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+(\.[a-zA-Z]{2,3})+$/;
		if (!mailReg.test(value)) {
			$notice.addClass("notice alert").text("邮箱格式错误");
			return false;
		} else {
			return true;
		}
	},
	// password : function (value1,value2) {
	// 	var $notice1 = $("input#password + span"),$notice2 = $("input#password_confirm + span");
	// 	if (!value1) {
	// 		$notice1.addClass("notice alert").text("密码不能为空");
	// 		return false;
	// 	} else if (value1.length < 6) {
	// 		$notice1.addClass("notice alert").text("密码长度不能小于6");
	// 		return false;
	// 	} else {
	// 		$notice1.removeClass("notice alert").text(" ");
	// 		if (arguments[1] && value1 != value2) {
	// 			$notice2.addClass("notice alert").text("两次密码输入不同");
	// 			return false;
	// 		} else {
	// 			$notice2.removeClass("notice alert").text(" ");
	// 			return true;
	// 		}
	// 	}
	// },
	phone : function (value) {
		var $notice = $("input#phone + span");
		if (!value) {
			$notice.addClass("notice alert").text("请填写您的手机号码");
			return false;
		}
		var phoneReg = /^((1[358]{1}[0-9]{1}))+\d{8}$/;
		if (!phoneReg.test(value)) {
			$notice.addClass("notice alert").text("手机号码格式错误");
			return false;
		}
		else $notice.removeClass("notice alert").text(" ");
		return true;
	},
	mini_phone : function(value){
		var $notice = $("input#mini_phone + span");
		if(!value){
			$notice.addClass("notice alert").text("若无短号，可不填写");
			return true;
		}
		var mini_phoneReg = /^\d{4,8}$/;
		if(!mini_phoneReg.test(value)){
			$notice.addClass("notice alert").text("手机短号格式错误");
			return false;
		}else{ 
			$notice.removeClass("notice alert").text(" ");
			return true;
		}
	},
	captcha : function(value)
	{
		var $notice = $("input#captcha + span");
		if(!value){
			$notice.addClass("notice alert").text("请输入验证码！");
			return false;
		} else {	
			$.get(document.URL+"/ajax_check",{captcha:$("#captcha").attr("value"),t:Math.random()},function(data){	
				if(data==0)	{ 	
					$notice.addClass("notice alert").text("验证码错误，请重试！");
				} else {
					$notice.removeClass("notice alert").text(" ");
				}
			});
			if($notice.text()==" ") {
				return true;
			} else {
				return false;
			} 
		}
	}
}
$(function(){

	$("input#mail").bind("blur", function(){
		check_func.email(this.value);
		});
	// $("input#password").bind("blur",function(){
	// 	check_func.password(this.value);
	// });
	// $("input#password_confirm").bind("blur",function(){
	// 	check_func.password($("input#password")[0].value, this.value);
	// });
	// $("input#name").bind("blur", function(){
	// 	check_func.name(this.value);
	// });
	// $("input#stu_id").bind("blur", function(){
	// 	check_func.stu_id(this.value);
	//})
	$("input#phone").bind("blur", function(){
		check_func.phone(this.value);
	});
	$("input#mini_phone").bind("blur",function(){
		check_func.mini_phone(this.value);
	});

	$("input#captcha").bind("blur", function(){
		check_func.captcha(this.value);
	});
	
	$("input[type=submit]").bind("click", function(){
		var check_control = true;
		check_control = check_func.email($("input#mail")[0].value);
		if(!check_control) return false; 
		// check_control = check_func.password($("input#password")[0].value, $("input#password_confirm")[0].value);
		check_control = check_func.phone($("input#phone")[0].value);
		if(!check_control) return false;
		// check_control = check_func.name($("input#name")[0].value);
		// check_control = check_func.stu_id($("input#stu_id")[0].value);
		// check_control = check_func.captcha($("input#captcha")[0].value);
		check_control = check_func.mini_phone($("input#mini_phone")[0].value);
		if(!check_control) return false;          
	});	
});
$(document).ready(function(){
    $(".ajaxForm").bind('submit', function(){//回调函数
        ajaxSubmit(this, function(data){  
        //document.write(data);  	
                        if (typeof data !== 'object') {
	        	    jsonobj = JSON.parse(data);
                        } else {
                            jsonobj = data;
                        }
	        	if(jsonobj.type=='alert')
	        	{
		            $("#popContent").html(jsonobj.content);
		            $("#pop_title").html(jsonobj.title);
		          	var h = $(document).height();
					$('#screen').css({ 'height': h });	
					$('#screen').show();
					$('.popbox').center();
					$('.popbox').fadeIn();

				}else
				if(jsonobj.type=='redirect')
				{
					if(typeof jsonobj.content!='undefined')
					{
						$("#popContent").html(jsonobj.content);
			            $("#pop_title").html(jsonobj.title);
			          	var h = $(document).height();
						$('#screen').css({ 'height': h });	
						$('#screen').show();
						$('.popbox').center();
						$('.popbox').fadeIn();
					}
					setTimeout("window.location.href='"+jsonobj.url+"'",1000);
				}
             });
        return false;
    });
    //关闭按钮
    $('.close-btn,.ok-btn ').click(function(){
		$('.popbox').fadeOut(function(){ $('#screen').hide(); 
		// window.top.location.reload();
	});
		return false;
	});
});
