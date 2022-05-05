var login_emailvisiable=$("#login_emailverify");
var login_pwvisiable=$("#login_pwverify");
var register_emailvisiable=$("#register_emailverify");
var register_pwvisiable=$("#register_pwverify");

function login_validemail(event){
    if(event.target.value.match(/^[\w\.]+@[\w]+(\.[\w]+)+$/)!=null||event.target.value==""){
        login_emailvisiable.css("visibility","hidden");
    }
    else {
        login_emailvisiable.css("visibility","visible");
        login_emailvisiable.html("Please input a valid email");
    }
}
function login_validpw(pw){
    if(pw.target.value.length>7){
        login_pwvisiable.css("visibility","hidden");
    }
    else {
        login_pwvisiable.css("visibility","visible");
        login_pwvisiable.html("The length of password need to larger than 8");
    }
}
function register_validemail(event){
    if(event.target.value.match(/^[\w\.]+@[\w]+(\.[\w]+)+$/)!=null||event.target.value==""){
        register_emailvisiable.css("visibility","hidden");
    }
    else {
        register_emailvisiable.css("visibility","visible");
        register_emailvisiable.html("Please input a valid email");
    }
}
function register_validpw(pw){
    if(pw.target.value.length>7){
        register_pwvisiable.css("visibility","hidden");
    }
    else {
        register_pwvisiable.css("visibility","visible");
        register_pwvisiable.html("The length of password need to larger than 8");
    }
}
$("#loginemail").on("input",login_validemail);
$("#loginpw").on("input",login_validpw);
$("#registeremail").on("input",register_validemail);
$("#registerpw").on("input",register_validpw);
$('.login_btns').click(function(e){
    e.preventDefault();  
    var form = $('#'+e.target.parentNode.id).serialize();
    form+="&action="+e.target.parentNode.id;
    $.ajax({
        url:'/admin/lib/LoginProcess.php',
        type : "POST",
        data : form,
        success : function(data) 
        {
            if(JSON.parse(data).url== undefined){
                alert(JSON.parse(data).alert);
            }else {
                alert(JSON.parse(data).alert);
                window.location.href = JSON.parse(data).url;   
            }
        },error: function(data) 
        {
            alert(data);
        }
    })
});
