$('#logout').click(function(e){
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
});