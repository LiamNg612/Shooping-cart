
$(".form-control").on('input',change_alert);
function change_alert(event){
    var target=event.target.parentElement.id;
    console.log(target);
    if(event.target.value.length<8){
        console.log(event.target.value.length);
        $('#'+target+' > small').css("visibility","visible");
        $('#'+target+' > small').text("Password length should be larger than 7");
    }
    else $('#'+target+' > small').css("visibility","hidden");
}


$("#confirmpw > input").on('change',identical);
function identical(event){
    var form=event.target.parentElement.id;
    var target=event.target.value;
    var compare=$("#newpw > input").val();
    if(target!=compare){
        $('#'+form+' > small').css("visibility","visible");
        $('#'+form+' > small').text("Password not the same");
    }
    else $('#'+form+' > small').css("visibility","hidden");
    
}

$("#changebtn").click(function(e){
    e.preventDefault();  
    var form = $('#'+e.target.parentNode.id).serialize();
    form+="&action="+e.target.parentNode.id;
    $.ajax({
        url:'/admin/lib/LoginProcess.php',
        type : "POST",
        data : form,
        success : function(data) 
        { 
           if(JSON.parse(data).message != undefined){
                alert(JSON.parse(data).message);
                $.ajax({
                    url:'/logout.php',
                    type : "POST",
                    data : {action:"logout"},
                    success : function(data) 
                    {
                        alert(data);
                        window.location.href = "/main.php"; 
            
                    },error: function(data) 
                    {
                        alert(data);
                    }
                })
            }else {
                alert(JSON.parse(data).alert);
            }
        },error: function(data) 
        {
           
        }
    })
});