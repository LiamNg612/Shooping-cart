
$('#form1').on("submit", checkout);
function checkout(e){
  e.preventDefault();
  var dict = new Array();
    const reg = new RegExp('^[0-9]$');
    for (var key in localStorage) {
        if (localStorage.hasOwnProperty(key)&&reg.test(key)) {
            dict.push({"PID":key,"QUANTITY":localStorage.getItem(key)});
        }
    }
    var setting=$( "#form1" ).serialize().split("&");
    setting.forEach((element,index)=>(setting[index]=element.split("=")[1]));
    $.ajax(
      {
          url: '/admin/lib/co_process.php',   // url
          method: "POST",
          async: true,
          data: { method: "checkout",cart: JSON.stringify(dict),setting:setting},// data to be submit
          success: function (data) {// success callback
              var order=JSON.parse(data)['custom_id'];
              var custom_id=JSON.parse(data)['custom_id'];
              var digest=JSON.parse(data)['digest'];
          },error: function(data) 
          {
              alert("Your order have some problem.Try again later.");
          }
    }
  );
}
window.onload = () =>{
  //$('#form1').submit();
  var dict = new Array();
  const reg = new RegExp('^[0-9]$');
  for (var key in localStorage) {
      if (localStorage.hasOwnProperty(key)&&reg.test(key)) {
          dict.push({"PID":key,"QUANTITY":localStorage.getItem(key)});
          //price += (JSON.parse(localStorage.getItem(key))) * parseFloat($("#" + key + "orginal_price").html());
      }
  }
  $.ajax(
      {
          url: '/admin/lib/db.inc.php',   // url
          method: "POST",
          async: true,
          data: { method: "cart list",cart: JSON.stringify(dict)},// data to be submit
          success: function (data) {// success callback
              $("#charset").after(data);
      }
  }
  );
}