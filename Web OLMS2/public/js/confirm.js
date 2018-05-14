var host = "127.0.0.1:8000";
function confirmDelete(id){
    var check = confirm("ยืนยันการลบ");

    if (check) {
        window.open("http://"+host+"/activity/delete/"+id,"_self");
    } 
}

function confirmAccept(id){
    var check = confirm("ยืนยันการอนุมัติ");
    
    if (check) {
        window.open("http://"+host+"/accept/"+id,"_self");
    }
}

function daleteAll(){
    var check = confirm("ยืนยันการลบ");
    if (check) {
        //window.open("http://localhost:8000/deleteall","_self");
    }
}
function acceptAll(){
    var check = confirm("ยืนยันการอนุมัติ");
    if (check) {
        //window.open("http://localhost:8000/acceptall","_self");
    }
}
function ejectAll(){
    var check = confirm("ยืนยันการปฏิเสธ");
    if (check) {
        //window.open("http://localhost:8000/ejectall","_self");
    }
}



function confirmEject(id){
    var check = confirm("ยืนยันการปฎิเสธ");

    if (check) {
        window.open("http://"+host+"/eject/"+id,"_self");
    } 
}

function checkBAll(params) {
    var x;
    for (let index = 0; index < params.length; index++) {
        const element = params[index];
        x = document.getElementById(index);
        if (x.checked) {
            x.checked = false;
        }else {
            x.checked = true;
        }
        //console.log(x.checked)
        
    }
}

function showSearch() {
    document.getElementById("show").style.display = "inline";
    document.getElementById("btnshow").style.display = "none";
}

