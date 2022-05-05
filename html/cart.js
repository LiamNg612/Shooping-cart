
//initial all button function
let page = 0;
let cart_btns = document.querySelectorAll(".add-cart");
let cart_list = document.querySelector("#cart > ul");
let amount;
function setbtn(){
    
    cart_btns = document.querySelectorAll(".add-cart");
    for (let i = 0; i < cart_btns.length; i++) {
        if(cart_btns[i].getAttribute('listener')!== 'true'){
            cart_btns[i].addEventListener('click', (event) =>{
                add(event.target);
            })
            cart_btns[i].setAttribute('listener','true');
        }
        
    }
}
setbtn();

window.onload = function () {
    for (var key in localStorage) {
        if (localStorage.hasOwnProperty(key)) {
            create_product(key);
            update(key, 0);
        }
    }
    $("#totalprice").html(total());
}

function add(product) {
    if (localStorage.getItem(product.value) === null) {
        localStorage.setItem(product.value, JSON.stringify(1));
        create_product(product.value);

    } else {
        update(product.value, 1);
        localStorage.setItem(product.value, JSON.stringify(JSON.parse(localStorage.getItem(product.value)) + 1));
    }
    $("#totalprice").html(total());
}
function create_product(pid) {
    let add_btn, minus_btn, del_btn;
    const reg = new RegExp('^[0-9]$');
    if(!reg.test(pid)){
        return 1;
    }
    $.ajax(
        {
            url: '/admin/lib/db.inc.php',   // url
            method: "POST",
            async: false,
            data: { method: 'pid', pid: pid },// data to be submit
            success: function (data) {// success callback
                $("#Cart>ul").append(data);
                $("#pid" + pid + "_amount").val(localStorage.getItem(pid));
                add_btn = document.querySelector("#pid" + pid + "_addbtn");
                minus_btn = document.querySelector("#pid" + pid + "_minusbtn");
                del_btn = document.querySelector("#pid" + pid + "_delbtn");
                input_box = document.querySelector("#pid" + pid + "_amount");
            }
        });
    add_btn.addEventListener("click", function (event) {
        addone(event.target.parentNode.parentNode.id);
        $("#totalprice").html(total());
    });
    minus_btn.addEventListener("click", function (event) {
        if (JSON.parse(localStorage.getItem(event.target.parentNode.parentNode.id)) > 0) {
            minusone(event.target.parentNode.parentNode.id);
            $("#totalprice").html(total());
        } else {
            localStorage.removeItem(event.target.parentNode.parentNode.id);
            $("#totalprice").html(total());
            event.target.parentNode.parentNode.remove();
        }

    });
    input_box.addEventListener("change", function (event) {
        updateamount(event);
        $("#totalprice").html(total());
    });
    del_btn.addEventListener("click", function (event) {
        localStorage.removeItem(event.target.parentNode.parentNode.id);
        $("#totalprice").html(total());
        event.target.parentNode.parentNode.remove();
    });
}

function update(pid, number) {
    $("#pid" + pid + "_amount").val(JSON.parse(localStorage.getItem(pid)) + number);
    $("#pid" + pid + "_price").html("$" + (JSON.parse(localStorage.getItem(pid)) + number) * parseFloat($("#" + pid + "orginal_price").html()));
}
function updateamount(event) {
    localStorage.setItem(event.target.parentNode.parentNode.id, event.target.value);
    update(event.target.parentNode.parentNode.id, 0);
}
function total() {
    let price = 0;
    var allprice = document.getElementsByClassName("total");
    const reg = new RegExp('^[0-9]$');
    for (var key in localStorage) {
        if (localStorage.hasOwnProperty(key)&&reg.test(key)) {
            price += (JSON.parse(localStorage.getItem(key))) * parseFloat($("#" + key + "orginal_price").html());
        }
    }
    return price;
}
function addone(pid) {
    update(pid, 1);
    localStorage.setItem(pid, JSON.stringify(JSON.parse(localStorage.getItem(pid)) + 1));
}
function minusone(pid) {
    update(pid, -1);
    localStorage.setItem(pid, JSON.stringify(JSON.parse(localStorage.getItem(pid)) - 1));
}
//to indentify it is cart.php or main php for scroll data
let current="";
let currentpid="";
let cart=false;
if((current=window.location.search.split("?")[1])!=null){
    currentpid=current.split("=")[1];
    cart=true;
}
//infinite scroll function
$(window).scroll(function () {
    if (parseInt($(window).scrollTop()) + $(window).height() == $(document).height()) {
        page ++;
        if(!cart){
            $.ajax(
                {
                    url: '/admin/lib/db.inc.php',   // url
                    method: "POST",
                    async: true,
                    data: { page: page },// data to be submit
                    success: function (data) {// success callback
                        $("#show_product").append(data);
                    }
                });
        }
        else {
            $.ajax(
                {
                    url: '/admin/lib/db.inc.php',   // url
                    method: "POST",
                    async: true,
                    data: { page: page,catid: currentpid},// data to be submit
                    success: function (data) {// success callback
                        $("#show_product").append(data);
                    }
                });
        }
    
        setbtn();
       
    }
});
